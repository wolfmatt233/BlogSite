<?php

namespace App\Controllers;

use App\Controller;

class MainController extends Controller
{
    public function home()
    {
        $this->render('main', 'home', ['title' => 'Home']);
    }

    public function error()
    {
        $this->render('main', 'error', ['title' => 'Error']);
    }
}