<?php

namespace App\Core\Routes;

use App\Core\Controller\Controller;

class RouteConfiguration
{

    public string $route;
    private string $controllerName;
    private string $index;

    public function __construct(string $route, string $controllerName, string $index)
    {
        $this->route = $route;
        $this->controllerName = $controllerName;
        $this->index = $index;
    }

    public function middleware(array $middleware): RouteConfiguration
    {
        //
        return $this;
    }

    public function getIndex(): string
    {
        return $this->index;
    }

    public function getControllerName(): string
    {
        return $this->controllerName;
    }

}