<?php

namespace App\Http\Middleware;

use docker\app\Core\Helpers\JWTHelper;
use docker\app\Core\Http\Response\JsonResponse;
use docker\app\Core\Http\Response\Response;
use Books

class AdminMiddleware extends AbstractMiddleware
{
    public function handle(): void
    {
        $token = JWTHelper::validateAccessToken($this->request);

        if (! $token || $token->data->role !== 'admin'){
            (new JsonResponse(Response::FORBIDDEN))->send();
        }
    }
}