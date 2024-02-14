<?php

namespace App\Core\Routes;


use App\Core\Http\Request\RequestInterface;

interface RouteInterface
{
    public function dispatch(string $uri, string $httpMethod, RequestInterface $request): void;
}