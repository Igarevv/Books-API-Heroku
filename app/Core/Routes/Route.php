<?php

namespace App\Core\Routes;

class Route
{

    private static array $routes = [];

    public static function get(string $uri, array $controllers): RouteConfiguration
    {
        $routeHandler = new RouteConfiguration($uri, $controllers[0], $controllers[1]);
        self::$routes[] = $routeHandler;
        return $routeHandler;
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

}
