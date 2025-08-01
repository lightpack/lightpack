/**
 * Lightpack Cable - Client-side library
 * 
 * This library provides a Socket.io-like API for real-time communication
 * using efficient polling.
 */
// --- GLOBAL AUDIO CACHE FOR NOTIFICATION SOUNDS ---
const _audioCache = {};

(function(window) {
    'use strict';
    
    /**
     * Cable client
     */
    class Cable {
        constructor(options = {}) {
            this.options = Object.assign({
                endpoint: '/cable/poll',
                pollInterval: 3000,
                reconnectInterval: 5000,
                maxReconnectAttempts: 5
            }, options);
            
            this.connected = false;
            this.reconnectAttempts = 0;
            this.lastIds = {};
            this.subscriptions = {};
            this.pollingIntervals = {};
            
            // --- Outgoing Event Batching Support ---
            this._outgoingBatch = [];
            this._batchSize = 10; // Default batch size
            this._batchInterval = 5000; // Default flush interval in ms
            this._batchEndpoint = '/api/batch-events'; // Default API endpoint
            this._batchIntervalId = null;
            this._csrfToken = null;
        }
        
        /**
         * Connect to the server
         */
        connect() {
            this.connected = true;
            this.startPolling();
            return this;
        }
        
        /**
         * Subscribe to a channel
         */
        subscribe(channel, handlers = {}) {
            if (!this.subscriptions[channel]) {
                this.subscriptions[channel] = {
                    events: {},
                    lastId: 0,
                    pollInterval: this.options.pollInterval,
                    lastPollTime: 0
                };
                
                // Start polling for this channel
                this.startChannelPolling(channel);
            }
            
            // Handle object-style event handlers
            if (typeof handlers === 'object' && handlers !== null) {
                Object.keys(handlers).forEach(event => {
                    if (typeof handlers[event] === 'function') {
                        if (!this.subscriptions[channel].events[event]) {
                            this.subscriptions[channel].events[event] = [];
                        }
                        this.subscriptions[channel].events[event].push(handlers[event]);
                    }
                });
            }
            
            const subscription = {
                on: (event, callback) => {
                    if (!this.subscriptions[channel].events[event]) {
                        this.subscriptions[channel].events[event] = [];
                    }
                    
                    this.subscriptions[channel].events[event].push(callback);
                    return subscription; // Return subscription object for chaining
                },
                filter: (filterFn) => {
                    this.subscriptions[channel].filter = filterFn;
                    
                    return {
                        on: (event, callback) => {
                            if (!this.subscriptions[channel].events[event]) {
                                this.subscriptions[channel].events[event] = [];
                            }
                            
                            // Wrap callback with filter
                            const wrappedCallback = (payload) => {
                                if (this.subscriptions[channel].filter(payload)) {
                                    callback(payload);
                                }
                            };
                            
                            this.subscriptions[channel].events[event].push(wrappedCallback);
                            return subscription; // Return subscription object for chaining
                        }
                    };
                }
            };
            
            return subscription;
        }
        
        /**
         * Start polling for updates
         */
        startPolling() {
            if (!this.connected) return;
            
            // Start polling for each channel
            Object.keys(this.subscriptions).forEach(channel => {
                this.startChannelPolling(channel);
            });
        }
        
        /**
         * Start polling for a specific channel
         */
        startChannelPolling(channel) {
            if (!this.connected || !this.subscriptions[channel]) return;
            
            // Clear any existing interval
            if (this.pollingIntervals[channel]) {
                clearInterval(this.pollingIntervals[channel]);
            }
            
            // Poll immediately
            this.pollChannel(channel);
            
            // Set up interval for this channel
            const interval = this.subscriptions[channel].pollInterval;
            this.pollingIntervals[channel] = setInterval(() => {
                this.pollChannel(channel);
            }, interval);
        }
        
        /**
         * Poll for updates for a specific channel
         */
        pollChannel(channel) {
            if (!this.connected || !this.subscriptions[channel]) return;
            
            const subscription = this.subscriptions[channel];
            subscription.lastPollTime = Date.now();
            
            fetch(`${this.options.endpoint}?channel=${encodeURIComponent(channel)}&lastId=${subscription.lastId}`)
                .then(response => {
                    if (response.status === 304) {
                        return []; // No new messages
                    }
                    return response.json();
                })
                .then(messages => {
                    if (messages.length > 0) {
                        // Update last ID
                        subscription.lastId = messages[messages.length - 1].id;
                        
                        // Process messages
                        messages.forEach(message => {
                            this.processMessage(channel, message);
                        });
                    }
                    
                    // Reset reconnect attempts on success
                    this.reconnectAttempts = 0;
                })
                .catch(error => {
                    console.error(`Cable polling error for channel ${channel}:`, error);
                    this.handleError(channel);
                });
        }
        
        /**
         * Poll for updates (legacy method, now just polls all channels)
         */
        poll() {
            const channels = Object.keys(this.subscriptions);
            channels.forEach(channel => this.pollChannel(channel));
        }
        
        /**
         * Process a message
         */
        processMessage(channel, message) {
            const subscription = this.subscriptions[channel];
            const event = message.event;
            const payload = message.payload || {};
            
            // Special handling for DOM updates
            if (event === 'dom-update' && payload.selector && payload.html) {
                const elements = document.querySelectorAll(payload.selector);
                elements.forEach(el => {
                    el.innerHTML = payload.html;
                });
            }
            
            // Handle batch events
            if (event === 'batch' && Array.isArray(payload.events)) {
                payload.events.forEach(batchEvent => {
                    this.triggerEvent(channel, batchEvent.event, batchEvent.payload);
                });
                return;
            }
            
            // Handle presence updates
            if (event === 'presence:update' && Array.isArray(payload.users)) {
                // Store previous users list for join/leave detection
                const previousUsers = this._getPresenceUsers(channel) || [];
                
                // Update presence state
                this._setPresenceUsers(channel, payload.users);
                
                // Detect joins and leaves
                const joined = payload.users.filter(id => !previousUsers.includes(id));
                const left = previousUsers.filter(id => !payload.users.includes(id));
                
                // Trigger presence events
                if (joined.length > 0) {
                    this.triggerEvent(channel, 'presence:join', { users: joined });
                }
                
                if (left.length > 0) {
                    this.triggerEvent(channel, 'presence:leave', { users: left });
                }
            }
            
            // Call event handlers
            this.triggerEvent(channel, event, payload);
        }
        
        /**
         * Trigger event handlers
         */
        triggerEvent(channel, event, payload) {
            const subscription = this.subscriptions[channel];
            
            if (subscription.events[event]) {
                subscription.events[event].forEach(callback => {
                    callback(payload);
                });
            }
        }
        
        /**
         * Handle connection error
         */
        handleError(channel) {
            this.reconnectAttempts++;
            
            if (this.reconnectAttempts <= this.options.maxReconnectAttempts) {
                // Exponential backoff
                const delay = this.options.reconnectInterval * Math.pow(2, this.reconnectAttempts - 1);
                
                console.log(`Cable reconnecting channel ${channel} in ${delay}ms (attempt ${this.reconnectAttempts})`);
                
                setTimeout(() => {
                    this.pollChannel(channel);
                }, delay);
            } else {
                console.error(`Cable max reconnect attempts reached for channel ${channel}`);
                this.disconnectChannel(channel);
            }
        }
        
        /**
         * Disconnect from the server
         */
        disconnect() {
            this.connected = false;
            
            // Clear all polling intervals
            Object.keys(this.pollingIntervals).forEach(channel => {
                clearInterval(this.pollingIntervals[channel]);
                delete this.pollingIntervals[channel];
            });
        }
        
        /**
         * Disconnect a specific channel
         */
        disconnectChannel(channel) {
            if (this.pollingIntervals[channel]) {
                clearInterval(this.pollingIntervals[channel]);
                delete this.pollingIntervals[channel];
            }
        }
        
        /**
         * Configure polling intervals for different channels
         */
        configure(channelConfig) {
            this.channelConfig = channelConfig;
            
            // Apply configuration
            Object.keys(channelConfig).forEach(channelPattern => {
                const config = channelConfig[channelPattern];
                
                // Find matching channels
                Object.keys(this.subscriptions).forEach(channel => {
                    if (channel.startsWith(channelPattern)) {
                        // Apply configuration
                        if (config.pollInterval) {
                            this.setPollInterval(channel, config.pollInterval);
                        }
                    }
                });
            });
        }
        
        /**
         * Set polling interval for a channel
         */
        setPollInterval(channel, interval) {
            if (!this.subscriptions[channel]) {
                this.subscriptions[channel] = {
                    events: {},
                    lastId: 0,
                    pollInterval: interval,
                    lastPollTime: 0
                };
            } else {
                this.subscriptions[channel].pollInterval = interval;
            }
            
            // Restart polling with new interval
            this.startChannelPolling(channel);
        }
        
        // --- Presence Channel Support ---
        
        /**
         * Get users present in a channel
         */
        getPresenceUsers(channel) {
            return this._getPresenceUsers(channel) || [];
        }
        
        /**
         * Check if a user is present in a channel
         */
        isUserPresent(channel, userId) {
            const users = this._getPresenceUsers(channel);
            return users ? users.includes(userId) : false;
        }
        
        /**
         * Internal: Get presence users from channel state
         */
        _getPresenceUsers(channel) {
            if (!this.subscriptions[channel]) return null;
            if (!this.subscriptions[channel].presence) return null;
            return this.subscriptions[channel].presence.users;
        }
        
        /**
         * Internal: Set presence users in channel state
         */
        _setPresenceUsers(channel, users) {
            if (!this.subscriptions[channel]) return;
            if (!this.subscriptions[channel].presence) {
                this.subscriptions[channel].presence = {};
            }
            this.subscriptions[channel].presence.users = users;
        }
        
        // --- Outgoing Event Batching Support ---
        
        _initBatching() {
            if (this._batchIntervalId) return; // Prevent multiple intervals
            this._batchIntervalId = setInterval(() => {
                this.flushOutgoingBatch();
            }, this._batchInterval);
        }
        
        emitBatched(channel, event, payload) {
            this._outgoingBatch.push({ channel, event, payload });
            if (this._outgoingBatch.length >= this._batchSize) {
                this.flushOutgoingBatch();
            }
            this._initBatching();
        }
        
        flushOutgoingBatch() {
            if (this._outgoingBatch.length === 0) return;
            // Determine CSRF token: use option, then meta tag, else error
            let csrfToken = this._csrfToken;
            if (!csrfToken) {
                const meta = document.querySelector('meta[name="csrf-token"]');
                if (meta) {
                    csrfToken = meta.getAttribute('content');
                }
            }
            if (!csrfToken) {
                console.error('CSRF token not found. Cannot send batch POST.');
                return;
            }
            fetch(this._batchEndpoint, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ events: this._outgoingBatch }),
            })
            .catch(err => {
                // Optionally handle errors (could retry, etc.)
                console.error('Failed to send batched events:', err);
            });
            this._outgoingBatch = [];
        }
        
        setBatchOptions({ batchSize, batchInterval, batchEndpoint, csrfToken } = {}) {
            if (typeof batchSize === 'number') this._batchSize = batchSize;
            if (typeof batchInterval === 'number') {
                this._batchInterval = batchInterval;
                if (this._batchIntervalId) {
                    clearInterval(this._batchIntervalId);
                    this._batchIntervalId = null;
                }
                this._initBatching();
            }
            if (typeof batchEndpoint === 'string') this._batchEndpoint = batchEndpoint;
            if (typeof csrfToken === 'string') this._csrfToken = csrfToken;
        }
        
        presence(channel, userId) {
            return {
                // Join a presence channel
                join: (endpoint = '/cable/presence/join') => {
                    return fetch(endpoint, {
                        method: 'POST',
                        headers: this._getHeaders(),
                        body: JSON.stringify({ channel, userId })
                    }).then(response => response.json());
                },
                
                // Leave a presence channel
                leave: (endpoint = '/cable/presence/leave') => {
                    console.log('endpoint')
                    console.log(endpoint)
                    return fetch(endpoint, {
                        method: 'POST',
                        headers: this._getHeaders(),
                        body: JSON.stringify({ channel, userId })
                    }).then(response => response.json());
                },
                
                // Start heartbeat
                startHeartbeat: (interval = 20000, endpoint = '/cable/presence/heartbeat') => {
                    if (this._heartbeatInterval) {
                        clearInterval(this._heartbeatInterval);
                    }
                    
                    this._heartbeatInterval = setInterval(() => {
                        fetch(endpoint, {
                            method: 'POST',
                            headers: this._getHeaders(),
                            body: JSON.stringify({ channel, userId })
                        });
                    }, interval);
                    
                    return this;
                },
                
                // Stop heartbeat
                stopHeartbeat: () => {
                    if (this._heartbeatInterval) {
                        clearInterval(this._heartbeatInterval);
                        this._heartbeatInterval = null;
                    }
                    return this;
                },
                
                // Get users in channel
                getUsers: (endpoint = '/cable/presence/users') => {
                    return fetch(endpoint, {
                        method: 'POST',
                        headers: this._getHeaders(),
                        body: JSON.stringify({ channel })
                    }).then(response => response.json());
                }
            };
        }
        
        _getHeaders() {
            const headers = {
                'Content-Type': 'application/json'
            };
            
            // Add CSRF token if available
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                headers['X-CSRF-TOKEN'] = token.getAttribute('content');
            }
            
            return headers;
        }
    }
    
    // Export to window
    window.cable = {
        // Track if user has interacted with the page
        _userInteracted: false,
        
        // Queue of sounds to play once interaction happens
        _soundQueue: [],
        
        /**
         * Connect to the server
         */
        connect(options) {
            return new Cable(options).connect();
        },
        
        /**
         * Play a sound file
         * @param {string} url - URL to the sound file
         * @param {number} volume - Volume from 0 to 1 (default: 1)
         * @return {Promise} - Resolves when the sound starts playing, rejects on error
         */
        playSound(url, volume = 1) {
            if (!this._userInteracted) {
                console.log(`Sound queued until user interaction: ${url}`);
                this._soundQueue.push({ url, volume });
                return Promise.resolve();
            }
            return new Promise((resolve, reject) => {
                try {
                    let audio = _audioCache[url];
                    if (!audio) {
                        audio = new Audio(url);
                        _audioCache[url] = audio;
                    }
                    audio.pause();
                    audio.currentTime = 0;
                    audio.volume = volume;
                    // Play the sound
                    const playPromise = audio.play();
                    if (playPromise !== undefined) {
                        playPromise.then(() => resolve(audio)).catch(error => {
                            console.error(`Browser blocked sound from ${url}:`, error);
                            reject(error);
                        });
                    } else {
                        resolve(audio);
                    }
                } catch (error) {
                    console.error(`Failed to play sound from ${url}:`, error);
                    reject(error);
                }
            });
        },
        
        /**
         * Initialize sound system - call this when the page loads
         */
        initSounds() {
            // Set up event listeners to detect user interaction
            const interactionEvents = ['click', 'touchstart', 'keydown', 'scroll'];
            
            const handleInteraction = () => {
                if (this._userInteracted) return;
                
                console.log('User interaction detected, sounds enabled');
                this._userInteracted = true;
                
                // Play any queued sounds
                if (this._soundQueue.length > 0) {
                    console.log(`Playing ${this._soundQueue.length} queued sounds`);
                    
                    // Play only the most recent sound to avoid sound spam
                    const sound = this._soundQueue[this._soundQueue.length - 1];
                    this.playSound(sound.url, sound.volume);
                    
                    // Clear the queue
                    this._soundQueue = [];
                }
                
                // Remove event listeners
                interactionEvents.forEach(event => {
                    document.removeEventListener(event, handleInteraction);
                });
            };
            
            // Add event listeners
            interactionEvents.forEach(event => {
                document.addEventListener(event, handleInteraction);
            });
            
            return this;
        }
    };
    
    // Initialize sounds when the script loads
    window.addEventListener('DOMContentLoaded', () => {
        window.cable.initSounds();
    });
})(window);
