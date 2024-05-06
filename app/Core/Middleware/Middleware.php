<?php

namespace App\Core\Middleware;


use App\Core\Http\Request\RequestInterface;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthMiddleware;

class Middleware
{
    public const MAP = [
      'auth'  => AuthMiddleware::class,
      'admin' => AdminMiddleware::class
    ];
    public static function resolve(string $key): string
    {
        $middleware = static::MAP[$key] ?? null;
        if (! $middleware) {
            throw new \Exception("Not matching middleware '{$key}'");
        }
        return $middleware;
    }

    public static function handleChain(array $middlewareKeys, RequestInterface $request): void
    {
        foreach ($middlewareKeys as $key) {
            $middlewareClass = static::resolve($key);
            $middlewareInstance = new $middlewareClass($request);
            $middlewareInstance->handle();
        }
    }
}