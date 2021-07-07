<?php

/**
 * ------------------------------------------------------------
 * IoC Container: Simplified Service Locator.
 * ------------------------------------------------------------
 */

$container = new Lightpack\Container\Container();

/**
 * ------------------------------------------------------------
 * Bind services in container.
 * ------------------------------------------------------------
 */

$services = require_once DIR_CONFIG . '/services.php';

foreach ($services as $service) {
    (new $service)->register($container);
}
