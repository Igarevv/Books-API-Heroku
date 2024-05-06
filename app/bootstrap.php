<?php

use docker\app\Core\Database\Database;
use docker\app\Core\Database\DatabaseInterface;
use docker\app\Core\Http\Request\Request;
use docker\app\Core\Http\Request\RequestInterface;
use docker\app\Core\Validator\Validator;
use docker\app\Core\Validator\ValidatorInterface;
use docker\app\Http\Model\Repository\Book\BookRepository;
use docker\app\Http\Model\Repository\Book\BookRepositoryInterface;
use docker\app\Http\Model\Repository\Token\TokenRepository;
use docker\app\Http\Model\Repository\Token\TokenRepositoryInterface;
use docker\app\Http\Model\Repository\User\UserRepository;
use docker\app\Http\Model\Repository\User\UserRepositoryInterface;

$this->container->bind(RequestInterface::class, function (){
    return Request::createFromGlobals();
});

$this->container->bind(ValidatorInterface::class, function (){
    return new Validator();
});


$this->container->bind(DatabaseInterface::class, function (){
    return new Database(new docker\app\Config\Config());
});

$this->container->bind(UserRepositoryInterface::class, UserRepository::class);
$this->container->bind(TokenRepositoryInterface::class, TokenRepository::class);
$this->container->bind(BookRepositoryInterface::class, BookRepository::class);