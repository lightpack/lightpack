<?php

/**
 * ------------------------------------------------------------
 * Lightpack PHP
 * 
 * Performant PHP web framework with small footprint.
 * ------------------------------------------------------------
 */

require __DIR__ . '/../bootstrap/init.php';

/**
 * ------------------------------------------------------------
 * Run our application and gather the response object.
 * ------------------------------------------------------------
 */

$response = Lightpack\App::run($container);

/**
 * ------------------------------------------------------------
 * Finally send the response.
 * ------------------------------------------------------------
 */

$response->send();
