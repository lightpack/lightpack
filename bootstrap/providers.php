<?php

/**
 * ------------------------------------------------------------
 * IoC Container: Simplified Service Locator.
 * ------------------------------------------------------------
 */

$GLOBALS['container'] = $container = new Lightpack\Container\Container();

/**
 * ------------------------------------------------------------
 * Bind service providers in container.
 * ------------------------------------------------------------
 */

$providers = require DIR_CONFIG . '/providers.php';
$providers = $providers['providers'];

foreach ($providers as $provider) {
    (new $provider)->register($container);
}
