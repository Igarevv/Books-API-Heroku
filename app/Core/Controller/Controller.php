<?php

namespace App\Core\Controller;

use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\ResponseInterface;

abstract class Controller
{
    protected RequestInterface $request;
    protected ResponseInterface $response;

    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }
    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }
    protected function request(): RequestInterface
    {
        return $this->request;
    }
    protected function response(): ResponseInterface
    {
        return $this->response;
    }
}