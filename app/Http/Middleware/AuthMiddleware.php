<?php

namespace App\Http\Middleware;

use App\Core\Helpers\JWTHelper;
use App\Core\Http\Response\JsonResponse;
use App\Core\Http\Response\Response;

class AuthMiddleware extends AbstractMiddleware
{
    public function handle(): void
    {
        $token = JWTHelper::validateAccessToken($this->request);
        if(! $token){
            (new JsonResponse(Response::UNAUTHORIZED))->send();
        }
    }
}