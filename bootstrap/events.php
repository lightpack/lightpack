<?php

/**
 * ------------------------------------------------------------
 * Subscribe to app events.
 * ------------------------------------------------------------
 */

$events = require DIR_CONFIG . '/events.php';
$events = $events['events'];

foreach ($events as $event => $listeners) {
    foreach ($listeners as $listener) {
        $container->get('event')->subscribe($event, $listener);
    }
}
