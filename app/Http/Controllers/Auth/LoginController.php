<?php

namespace App\Http\Controllers\Auth;

use App\Core\Controller\Controller;
use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\JsonResponse;
use App\Core\Http\Response\Response;
use App\Http\Exceptions\LoginException;
use App\Http\Exceptions\UserNotFoundException;
use App\Http\Service\Auth\LoginService;

class LoginController extends Controller
{

    public function __construct(
      protected LoginService $loginService
    ) {}

    public function login(RequestInterface $request): JsonResponse
    {
        $data = $request->input();
        try {
            $userDto = $this->loginService->getUserByEmail($data);

            $tokens = $this->loginService->login($userDto, $data['password']);

            setcookie('_logid', $tokens['refreshToken'], $_ENV['REFRESH_LIVE_TIME'],
              path: '/api/auth', httponly: true);

            return new JsonResponse(Response::OK, $tokens);
        } catch (LoginException|UserNotFoundException $e) {
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }

    public function refresh(RequestInterface $request)
    {
        $token = $request->cookie['_logid'] ?? '';
        try {
            $newTokens = $this->loginService->refresh($token);

            setcookie('_logid', $newTokens['refreshToken'], $_ENV['REFRESH_LIVE_TIME'],
              path: '/api/auth', httponly: true);

            return new JsonResponse(Response::OK, $newTokens);
        } catch (LoginException $e) {
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }
}
