<?php

/**
 * ------------------------------------------------------------
 * Use Composer Autoloader.
 * ------------------------------------------------------------
 */

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * ------------------------------------------------------------
 * Require Defined Constants
 * ------------------------------------------------------------
 */

require_once __DIR__ . '/../bootstrap/constants.php';

/**
 * ------------------------------------------------------------
 * Configure Error/Exception Reporting.
 * ------------------------------------------------------------
 */

require_once DIR_BOOTSTRAP . '/debug.php';

/**
 * ------------------------------------------------------------
 * Bind Services in IoC Container.
 * ------------------------------------------------------------
 */

require_once DIR_BOOTSTRAP . '/services.php';

/**
 * ------------------------------------------------------------
 * Discover app events.
 * ------------------------------------------------------------
 */

require_once DIR_BOOTSTRAP . '/events.php';

/**
 * ------------------------------------------------------------
 * Discover app routes and modules.
 * ------------------------------------------------------------
 */

require_once DIR_BOOTSTRAP . '/modules.php';

/**
 * ------------------------------------------------------------
 * Run our application.
 * ------------------------------------------------------------
 */

\Framework\App::run($container);