<?php

namespace App\Http\Exceptions;


use App\Core\Http\Response\Response;

class UserNotFoundException extends \Exception
{
    public static function userNotFound(): static
    {
        return new static('User not found', Response::NOT_FOUND);
    }
}