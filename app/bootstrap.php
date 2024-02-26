<?php

use App\Core\Database\Database;
use App\Core\Database\DatabaseInterface;
use App\Core\Http\Request\Request;
use App\Core\Http\Request\RequestInterface;
use App\Core\Validator\Validator;
use App\Core\Validator\ValidatorInterface;
use App\Http\Model\Repository\Token\TokenRepository;
use App\Http\Model\Repository\Token\TokenRepositoryInterface;
use App\Http\Model\Repository\User\UserRepository;
use App\Http\Model\Repository\User\UserRepositoryInterface;

$this->container->bind(RequestInterface::class, function (){
    return Request::createFromGlobals();
});

$this->container->bind(ValidatorInterface::class, function (){
    return new Validator();
});


$this->container->bind(DatabaseInterface::class, function (){
    return new Database($this->config);
});

$this->container->bind(UserRepositoryInterface::class, UserRepository::class);
$this->container->bind(TokenRepositoryInterface::class, TokenRepository::class);