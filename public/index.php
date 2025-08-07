<?php

/**
 * Use Composer Autoloader.
 */

require __DIR__ . '/../vendor/autoload.php';

/**
 * Boot directory constants.
 */

require __DIR__ . '/../boot/constants.php';


/**
 * Boot the web framework.
 */

Lightpack\App::boot();

/**
 * Run the app and send the response.
 */

Lightpack\App::run()->send();
