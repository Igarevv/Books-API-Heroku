<?php

namespace App\Http\Controllers\Auth;

use App\Core\Controller\Controller;
use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\JsonResponse;
use App\Core\Http\Response\Response;
use App\Http\Exceptions\DataException;
use App\Http\Service\Auth\RegisterService;

class RegisterController extends Controller
{

    public function __construct(
      protected RegisterService $registerService,
    ) {}

    public function register(RequestInterface $request): JsonResponse
    {
        $userData = $request->input();
        try {
            if (! $this->registerService->validate($userData)) {
                return new JsonResponse(Response::BAD_REQUEST,
                  $this->registerService->errors()
                );
            }

            $this->registerService->createUser($userData);

            return new JsonResponse(Response::CREATED);
        } catch (DataException $e) {
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }
}
