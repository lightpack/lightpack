<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        pp(app('response'));
        dd(app('response'));
        app('response')->render('home');
    }
}