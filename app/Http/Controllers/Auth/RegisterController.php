<?php

namespace App\Http\Controllers\Auth;

use docker\app\Core\Controller\Controller;
use docker\app\Core\Http\Request\RequestInterface;
use docker\app\Core\Http\Response\JsonResponse;
use docker\app\Core\Http\Response\Response;
use docker\app\Http\Exceptions\DataException;
use docker\app\Http\Service\Auth\RegisterService;

class RegisterController extends Controller
{

    public function __construct(
      protected docker\app\Http\Service\Auth\RegisterService $registerService,
    ) {}

    public function register(RequestInterface $request): JsonResponse
    {
        $userData = $request->input();
        try {
            if (! $this->registerService->validate($userData)) {
                return new JsonResponse(Response::BAD_REQUEST,
                  $this->registerService->errors(), true);
            }

            $this->registerService->createUser($userData);

            return new JsonResponse(Response::CREATED);
        } catch (DataException $e) {
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }

}
