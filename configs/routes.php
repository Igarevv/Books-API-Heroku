<?php

namespace App\Config;

use App\Core\Routes\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;

return [
  Route::get('/', [HomeController::class, 'index']),
  Route::post('/register', [RegisterController::class, 'register']),
];