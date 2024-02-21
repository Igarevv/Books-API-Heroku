<?php

namespace App\Http\Controllers\Auth;

use App\Core\Controller\Controller;
use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\JsonResponse;
use App\Core\Http\Response\Response;
use App\Http\Service\Auth\LoginService;

class LoginController extends Controller
{
    public function __construct(
      protected LoginService $loginService
    ) {}

    public function login(RequestInterface $request): JsonResponse
    {
        $data = $request->input();

        if(! $this->loginService->isInputFormatValid($data)){
            return new JsonResponse(Response::BAD_REQUEST);
        }

        $userDto = $this->loginService->getUserByEmail($data);
        if (! $userDto) {
            return new JsonResponse(Response::NOT_FOUND);
        }

        $tokens = $this->loginService->login($userDto, $data['password']);
        if ($tokens !== false) {
            return new JsonResponse(Response::OK, $tokens);
        }

        return new JsonResponse(Response::OK,
          ['errors' => 'Wrong email or password']);
    }

    public function refresh(RequestInterface $request)
    {
        $token = $request->cookie['_logid'];

        if(! $token){
            return new JsonResponse(Response::UNAUTHORIZED);
        }
        $newTokens = $this->loginService->refresh($token);
        if(! $newTokens){
            return new JsonResponse(Response::UNAUTHORIZED);
        }
        return new JsonResponse(Response::OK, $newTokens);
    }
}
