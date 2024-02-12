<?php

namespace App\Http\Controllers;

use App\Core\Controller\Controller;

class OrderController extends Controller
{
    public function index($userId, $orderId)
    {
        echo "This is order controller. Your id - {$userId}, your order id - {$orderId}";
    }

}