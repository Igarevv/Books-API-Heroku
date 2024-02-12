<?php

namespace App\Http\Controllers;
use App\Core\Controller\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        echo "Welcome to Books API";
    }
}