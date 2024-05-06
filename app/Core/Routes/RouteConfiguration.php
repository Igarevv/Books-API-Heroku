<?php

namespace App\Core\Routes;

class RouteConfiguration
{

    public string $route;
    private string $controllerName;
    private string $index;
    private ?array $middleware = null;

    public function __construct(string $route, string $controllerName, string $index)
    {
        $this->route = $route;
        $this->controllerName = $controllerName;
        $this->index = $index;
    }

    public function only(string $middleware): docker\app\Core\Routes\RouteConfiguration
    {
        $this->middleware[] = $middleware;
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

    public function getMiddleware(): ?array
    {
        return $this->middleware;
    }

}