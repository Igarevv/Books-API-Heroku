<?php

namespace App\Config;

use App\Core\Routes\Route;
use App\Http\Controllers\HomeController;

return [
  Route::get('/users/{id}/order', ['UserController::class', 'index']),
  Route::get('/posts/{post}', [HomeController::class, 'index']),
  Route::get('/users/{id}/order/50', ['ProfileController::class', 'index']),
];