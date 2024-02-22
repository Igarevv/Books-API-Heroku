<?php

namespace App\Http\Controllers\Auth;

use App\Core\Controller\Controller;
use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\JsonResponse;
use App\Core\Http\Response\Response;
use App\Http\Service\Auth\RegisterService;

class RegisterController extends Controller
{
    protected array $userData = [];

    public function __construct(
      protected RegisterService $registerService,
    ) {}

    public function register(RequestInterface $request): JsonResponse
    {
        $this->userData = $request->input();

        if (! $this->userData) {
            return new JsonResponse(Response::UNPROCESSABLE);
        }
        if (! $this->registerService->validate($this->userData)) {
            return new JsonResponse(Response::BAD_REQUEST,
              $this->registerService->errors()
            );
        }

        $created = $this->registerService->createUser($this->userData);

        if (! $created) {
            return new JsonResponse(Response::SERVER_ERROR);
        }
        return new JsonResponse(Response::CREATED);
    }
}
