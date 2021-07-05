<?php

$module = $container->get('module');
$route = $container->get('route');

/**
 * Register current module routes.
 */

if ($module->isDiscovered()) {
    require_once $module->getModuleRoutesFilePath();
} else {
    require_once DIR_CONFIG . '/routes.php';
}

/**
 * Register per module event configurations.
 */

$activeModules = $module->getActiveModules();

foreach ($activeModules as $activeModule) {
    $moduleConfig = require_once DIR_MODULES . '/' . $activeModule . '/config.php';

    foreach ($moduleConfig['events'] as $event => $listeners) {
        foreach ($listeners as $listener) {
            $container->get('event')->subscribe($event, $listener);
        }
    }
}
