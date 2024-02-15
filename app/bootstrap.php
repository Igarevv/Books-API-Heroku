<?php

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Core\Http\Request\Request;
use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\Response;
use App\Core\Http\Response\ResponseInterface;
use App\Core\Validator\Validator;
use App\Core\Validator\ValidatorInterface;

$this->container->bind(ConfigInterface::class, Config::class);

$this->container->bind(RequestInterface::class, function (){
    return Request::createFromGlobals();
});

$this->container->bind(ValidatorInterface::class, function (){
    return new Validator();
});

$this->container->bind(ResponseInterface::class, function (){
    return new Response();
});