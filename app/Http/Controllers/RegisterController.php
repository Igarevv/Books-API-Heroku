<?php

namespace App\Http\Controllers;

use App\Core\Controller\Controller;
use App\Core\Http\Json\JsonParser;
use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\ResponseInterface;
use App\Http\Service\Register\RegisterService;


class RegisterController extends Controller
{
    private array $userData = [];

    public function __construct(
      private RegisterService $registerService,
      private ResponseInterface $response
    )
    {}

    public function register(RequestInterface $request): void
    {
        $this->userData = $request->inputJsonData();
        if(! $this->userData){
            $this->response
              ->status(ResponseInterface::UNPROCESSABLE)
              ->message('Unprocessable data')
              ->send();
        }
        if(! $this->registerService->validate($this->userData)){

            [$status, $message] = $this->response
              ->status(ResponseInterface::BAD_REQUEST)
              ->message('Bad request')
              ->get();

            JsonParser::response([
              'status'  => $status,
              'message' => $message,
              'errors'  => $this->registerService->errors(),
            ]);
        }

        $this->registerService->insertData($this->userData);
    }

}
