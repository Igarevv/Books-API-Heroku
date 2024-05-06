<?php

namespace App\Http\Middleware;

use docker\app\Core\Http\Request\RequestInterface;
use docker\app\Http\Model\Repository\Token\TokenRepository;
use docker\app\Http\Service\Auth\TokenService;

abstract class AbstractMiddleware
{
    public function __construct(
      protected RequestInterface $request,
    )
    {}
    abstract public function handle();
}