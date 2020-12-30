<?php

/**
 * Register app events.
 */

foreach(require_once DIR_CONFIG . '/events.php' as $event => $listeners) {
    foreach($listeners as $listener) {
        $container->get('event')->subscribe($event, $listener);
    }
}