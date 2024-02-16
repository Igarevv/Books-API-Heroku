<?php

namespace App\Http\Controllers\Auth;

use App\Core\Controller\Controller;
use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\ResponseInterface;
use App\Http\Model\User\UserModel;
use App\Http\Service\Auth\LoginService;

class LoginController extends Controller
{

    public function __construct(
      protected ResponseInterface $response,
      protected LoginService $loginService
    ) {}

    public function login(RequestInterface $request)
    {
        $data = $request->input();
        $userDto = $this->loginService->getUserDTO($data);

        if (! $userDto) {
            $this->response
              ->status(ResponseInterface::NOT_FOUND)
              ->message('User not found')
              ->send();
        }

        $this->loginService->login($userDto, $data['password']);
    }

}