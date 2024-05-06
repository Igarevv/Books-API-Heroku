<?php

namespace App\Http\Controllers;
use docker\app\Core\Controller\Controller;
use docker\app\Core\Http\Response\JsonResponse;
use docker\app\Core\Http\Response\Response;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse(Response::OK, 'Books API home page');
    }
}