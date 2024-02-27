<?php

namespace App\Config;

use App\Core\Routes\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;

return [
  Route::get('/', [HomeController::class, 'index']),
  Route::post('/api/auth/login', [LoginController::class, 'login']),
  Route::post('/api/auth/register', [RegisterController::class, 'register']),
  Route::post('/api/auth/refresh-tokens', [LoginController::class, 'refresh']),
  Route::post('/api/auth/logout', [LoginController::class, 'logout']),
  Route::get('/api/show', [HomeController::class, 'index'])->middleware('auth'),
  Route::post('/api/admin/books', [BookController::class, 'create']),
];