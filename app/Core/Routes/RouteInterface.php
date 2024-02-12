<?php

namespace App\Core\Routes;

interface RouteInterface
{

    public function dispatch(string $uri, string $httpMethod): void;

}