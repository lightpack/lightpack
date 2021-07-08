<?php

/**
 * ------------------------------------------------------------
 * IoC Container: Simplified Service Locator.
 * ------------------------------------------------------------
 */

$container = new Lightpack\Container\Container();

/**
 * ------------------------------------------------------------
 * Bind service providers in container.
 * ------------------------------------------------------------
 */

$providers = require_once DIR_CONFIG . '/providers.php';

foreach ($providers as $provider) {
    (new $provider)->register($container);
}
