<?php

namespace App\Controllers;

use Lightpack\File\File;

class HomeController
{
    public function index()
    {
        app('response')->render('home', [
            'title' => 'Lightpack PHP', 
            'message' => 'A modern PHP web framework with extreme performance and small footprint.',
            'filex' => new File,
        ]);
    }
}