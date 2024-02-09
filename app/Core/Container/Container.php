<?php

namespace App\Core\Container;
use App\Core\Request\Request;
use App\Core\Request\RequestInterface;
use App\Core\Routes\Route;
use App\Core\Routes\RouteConfiguration;
use App\Core\Routes\RouteInterface;
use App\Core\Routes\Router;

class Container
{
    public readonly RouteInterface $router;
    public readonly RequestInterface $request;
    public function __construct()
    {
        $this->createDependencies();
    }

    private function createDependencies(): void
    {
        $this->request = Request::createFromGlobals();
        $this->router = new Router(
          $this->request
        );
    }
}