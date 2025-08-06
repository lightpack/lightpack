<?php

/**
 * ------------------------------------------------------------
 * Boot app providers.
 * ------------------------------------------------------------
 */

$providers = require DIR_CONFIG . '/providers.php';

Lightpack\App::bootProviders($providers['providers']);
