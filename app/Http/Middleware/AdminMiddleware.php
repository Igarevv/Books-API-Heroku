<?php

namespace App\Http\Middleware;

use App\Core\Helpers\JWTHelper;
use App\Core\Http\Response\JsonResponse;
use App\Core\Http\Response\Response;

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