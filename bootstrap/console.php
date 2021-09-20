<?php

require_once __DIR__ . '/constants.php';

/**
 * ------------------------------------------------------------
 * Require Defined Constants
 * ------------------------------------------------------------
 */

require_once __DIR__ . '/constants.php';

/**
 * ------------------------------------------------------------
 * Configure Environment Settings
 * ------------------------------------------------------------
 */

require_once DIR_BOOTSTRAP . '/environment.php';

/**
 * ------------------------------------------------------------
 * Bind Providers in IoC Container.
 * ------------------------------------------------------------
 */

require_once DIR_BOOTSTRAP . '/providers.php';

/**
 * ------------------------------------------------------------
 * Discover app events.
 * ------------------------------------------------------------
 */

require_once DIR_BOOTSTRAP . '/events.php';

/**
 * ------------------------------------------------------------
 * Register application specific console commands.
 * ------------------------------------------------------------
 */

require_once DIR_BOOTSTRAP . '/commands.php';
