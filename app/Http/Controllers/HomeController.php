<?php

namespace App\Http\Controllers;

use App\Core\Controller\Controller;
use App\Core\Http\Response\JsonResponse;
use App\Core\Http\Response\Response;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse(Response::OK, 'Books API home page');
    }
}