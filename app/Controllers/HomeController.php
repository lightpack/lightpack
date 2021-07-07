<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // print_r(app('db')->table('artists')->fetchAll());
        app('response')->render('home');
    }
}