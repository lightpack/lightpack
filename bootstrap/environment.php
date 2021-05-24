<?php

/**
 * Setup environment variables.
 */

if (file_exists(DIR_ROOT . '/env.php')) {
    foreach (require_once DIR_ROOT . '/env.php' as $key => $value) {
        set_env($key, $value);
    }
}
