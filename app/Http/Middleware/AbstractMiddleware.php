<?php

namespace App\Http\Middleware;

use App\Core\Http\Request\RequestInterface;
use App\Http\Model\Repository\Token\TokenRepository;
use App\Http\Service\Auth\TokenService;

abstract class AbstractMiddleware
{
    public function __construct(
      protected RequestInterface $request,
    )
    {}
    public abstract function handle();
}