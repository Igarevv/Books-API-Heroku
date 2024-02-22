<?php

namespace App\Core\Routes;

class RouteConfiguration
{

    public string $route;
    private string $controllerName;
    private string $index;
    private ?string $middleware = null;

    public function __construct(string $route, string $controllerName, string $index)
    {
        $this->route = $route;
        $this->controllerName = $controllerName;
        $this->index = $index;
    }

    public function middleware(string $middleware): RouteConfiguration
    {
        $this->middleware = $middleware;
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

    public function getMiddleware(): ?string
    {
        return $this->middleware;
    }

}