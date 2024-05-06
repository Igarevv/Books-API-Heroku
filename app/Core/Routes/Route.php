<?php

namespace App\Core\Routes;

use Booksuse Booksuse Booksuse Booksuse Booksuse Booksuse Booksuse Booksclass Route
{
    private static array $routes = [
      'GET'    => [],
      'POST'   => [],
      'PUT'    => [],
      'PATCH'  => [],
      'DELETE' => [],
    ];

    public static function get(string $uri, array|callable $controllers): RouteConfiguration
    {
        $routeHandler = new RouteConfiguration($uri, $controllers[0], $controllers[1]);
        self::$routes['GET'][] = $routeHandler;
        return $routeHandler;
    }

    public static function post(string $uri, array $controllers): RouteConfiguration
    {
        $routeHandler = new RouteConfiguration($uri, $controllers[0], $controllers[1]);
        self::$routes['POST'][] = $routeHandler;
        return $routeHandler;
    }
    public static function put(string $uri, array $controllers): RouteConfiguration
    {
        $routeHandler = new RouteConfiguration($uri, $controllers[0], $controllers[1]);
        self::$routes['PUT'][] = $routeHandler;
        return $routeHandler;
    }

    public static function delete(string $uri, array $controllers): RouteConfiguration
    {
        $routeHandler = new RouteConfiguration($uri, $controllers[0], $controllers[1]);
        self::$routes['DELETE'][] = $routeHandler;
        return $routeHandler;
    }

    public static function getRoutes(string $method): array
    {
        return self::$routes[$method];
    }

}
