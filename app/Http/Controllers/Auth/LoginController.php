<?php

namespace App\Http\Controllers\Auth;

use docker\app\Core\Controller\Controller;
use docker\app\Core\Cookie\Cookie;
use docker\app\Core\Http\Request\RequestInterface;
use docker\app\Core\Http\Response\JsonResponse;
use docker\app\Core\Http\Response\Response;
use docker\app\Http\Exceptions\LoginException;
use docker\app\Http\Exceptions\NotFoundException;
use docker\app\Http\Service\Auth\LoginService;

class LoginController extends Controller
{

    public function __construct(
      protected docker\app\Http\Service\Auth\LoginService $loginService
    ) {}

    public function login(RequestInterface $request): JsonResponse
    {
        $data = $request->input();
        try {
            $userDto = $this->loginService->getUserByEmail($data);

            $tokens = $this->loginService->login($userDto, $data['password']);

            return new JsonResponse(Response::OK, ['user_id' => $userDto->getUserId(), 'tokens' => $tokens]);
        } catch (LoginException|NotFoundException $e) {
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }

    public function refresh(RequestInterface $request): JsonResponse
    {
        $token = $request->input('token')['token'] ?? $request->cookie['_logid'] ?? '';

        try {
            $newTokens = $this->loginService->refresh($token);

            return new JsonResponse(Response::OK, $newTokens);
        } catch (LoginException $e) {
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }

    public function logout(RequestInterface $request): JsonResponse
    {
        $token = $request->input('token')['token'] ?? $request->cookie['_logid'] ?? '';

        if(! $token){
            return new JsonResponse(Response::UNAUTHORIZED);
        }

        $this->loginService->logout($token);

        return new JsonResponse(Response::OK, 'See you');
    }
}
