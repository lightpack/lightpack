<?php

use App\Controllers\HomeController;

/**
 * --------------------------------------------------
 * Register web routes here.
 * --------------------------------------------------
 */

route()->get('/', HomeController::class, 'index');
