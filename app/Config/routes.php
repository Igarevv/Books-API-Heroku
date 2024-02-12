<?php

namespace App\Config;

use App\Core\Routes\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;

return [
  Route::get('/', [HomeController::class, 'index']),
  Route::post('/register', ['RegisterController::class', 'index']),
  Route::get('/users/{userId}/orders/{orderId}', [OrderController::class, 'index'])
];