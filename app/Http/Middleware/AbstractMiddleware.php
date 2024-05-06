<?php

namespace App\Http\Middleware;

use App\Core\Http\Request\RequestInterface;

abstract class AbstractMiddleware
{
    public function __construct(
      protected RequestInterface $request,
    )
    {}
    abstract public function handle();
}