<?php

namespace App\Http\Controllers\Auth;

use App\Core\Controller\Controller;
use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\ResponseInterface;
use App\Http\Service\Auth\RegisterService;

class RegisterController extends Controller
{

    protected array $userData = [];

    public function __construct(
      protected RegisterService $registerService,
      protected ResponseInterface $response
    ) {}

    public function register(RequestInterface $request): void
    {
        $this->userData = $request->input();

        if (! $this->userData) {
            $this->response
              ->status(ResponseInterface::UNPROCESSABLE)
              ->message('Unprocessable data')
              ->send();
        }
        if (! $this->registerService->validate($this->userData)) {
            $this->response
              ->status(ResponseInterface::BAD_REQUEST)
              ->message('Bad request')
              ->data($this->registerService->errors(), true)
              ->send();
        }

        $created = $this->registerService->insertData($this->userData);
        if ($created) {
            $this->response->status(ResponseInterface::CREATED)
              ->message('Successfully created')
              ->send();
        }
    }
}
