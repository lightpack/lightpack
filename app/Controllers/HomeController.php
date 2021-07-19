<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        app('response')->render('home');
    }
}