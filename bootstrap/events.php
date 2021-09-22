<?php

/**
 * ------------------------------------------------------------
 * Subscribe to app events.
 * ------------------------------------------------------------
 */

foreach (config('events') as $event => $listeners) {
    foreach ($listeners as $listener) {
        $container->get('event')->subscribe($event, $listener);
    }
}
