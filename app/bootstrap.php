<?php

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Core\Database\Database;
use App\Core\Http\Request\Request;
use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\Response;
use App\Core\Http\Response\ResponseInterface;
use App\Core\Validator\Validator;
use App\Core\Validator\ValidatorInterface;

$this->container->bind(ConfigInterface::class, Config::class);


$this->container->bind(ValidatorInterface::class, Validator::class);

$this->container->bind(RequestInterface::class, function (){
    return Request::createFromGlobals();
});
$this->container->bind(ResponseInterface::class, Response::class);