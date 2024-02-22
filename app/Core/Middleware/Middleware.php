<?php

namespace App\Core\Middleware;

use App\Core\Http\Request\RequestInterface;
use App\Http\Middleware\AuthMiddleware;

class Middleware
{
    const MAP = [
      'auth' => AuthMiddleware::class,
    ];
    public static function resolve(string $key): string
    {
        $middleware = static::MAP[$key] ?? false;

        if(! $middleware){
            throw new \Exception("Not matching middleware {$key}");
        }
        return $middleware;
    }
}