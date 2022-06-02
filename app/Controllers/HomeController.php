<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        response()->render('home');
    }
}