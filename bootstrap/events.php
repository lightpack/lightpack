<?php

/**
 * Register app events.
 */
$events = require_once DIR_CONFIG . '/events.php';

foreach ($events as $event => $listeners) {
    foreach ($listeners as $listener) {
        $container->get('event')->subscribe($event, $listener);
    }
}
