<?php

/**
 * ------------------------------------------------------------
 * Setup environment variables.
 * ------------------------------------------------------------
 */

if (file_exists(DIR_ROOT . '/env.php')) {
    $env = require DIR_ROOT . '/env.php';

    foreach ($env as $key => $value) {
        set_env($key, $value);
    }
}
