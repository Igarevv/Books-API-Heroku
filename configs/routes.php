<?php

namespace App\Config;

use App\Core\Routes\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;

return [
  Route::get('/', [HomeController::class, 'index']),
  Route::post('/api/login', [LoginController::class, 'login']),
  Route::post('/api/register', [RegisterController::class, 'register']),
];