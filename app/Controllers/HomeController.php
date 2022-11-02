<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        return response()->view('home');
    }
}