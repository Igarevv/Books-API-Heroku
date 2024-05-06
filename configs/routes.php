<?php

namespace App\Config;

use docker\app\Core\Routes\Route;
use docker\app\Http\Controllers\Auth\LoginController;
use docker\app\Http\Controllers\Auth\RegisterController;
use docker\app\Http\Controllers\BookController;
use docker\app\Http\Controllers\FileController;
use docker\app\Http\Controllers\HomeController;
use docker\app\Http\Controllers\UserController;

return [
  Route::get('/api', [HomeController::class, 'index']),
  Route::post('/api/auth/login', [LoginController::class, 'login']),
  Route::post('/api/auth/register', [RegisterController::class, 'register']),
  Route::post('/api/auth/refresh-tokens', [LoginController::class, 'refresh']),
  Route::post('/api/auth/logout', [LoginController::class, 'logout']),
  Route::post('/api/admin/books', [BookController::class, 'create'])->only('auth')->only('admin'),
  Route::delete('/api/admin/books/{book_id}', [BookController::class, 'deleteBook'])->only('auth')->only('admin'),
  Route::post('/api/admin/books/save', [FileController::class, 'saveBooksInCSV'])->only('admin'),
  Route::get('/api/books', [BookController::class, 'index']),
  Route::get('/api/books/favorite', [UserController::class, 'index'])->only('auth'),
  Route::get('/api/books/{book_id}', [BookController::class, 'showOneBook']),
  Route::post('/api/books/favorite/{book_id}', [UserController::class, 'addUserFavoriteBook'])->only('auth'),
  Route::delete('/api/books/favorite/{book_id}', [UserController::class, 'deleteFavoriteBook'])->only('auth'),
  Route::get('/api/books/favorite/{book_id}', [UserController::class, 'showOneBook'])->only('auth'),
    // /api/books?limit={}
];