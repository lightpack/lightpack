<?php

/**
 * Register app events.
 */
$events = $container->get('config')->get('events');

foreach ($events as $event => $listeners) {
    foreach ($listeners as $listener) {
        $container->get('event')->subscribe($event, $listener);
    }
}
