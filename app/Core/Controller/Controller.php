<?php

namespace App\Core\Controller;

use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\Response;
use App\Core\Http\Response\ResponseInterface;


abstract class Controller
{
    private RequestInterface $request;
    public ResponseInterface $response;

    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }
    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }
    public function request(): RequestInterface
    {
        return $this->request;
    }
}