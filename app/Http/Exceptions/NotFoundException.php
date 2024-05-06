<?php

namespace App\Http\Exceptions;

use App\Core\Http\Response\Response;

class NotFoundException extends \Exception
{
    public static function userNotFound(): static
    {
        return new static('User not found', Response::NOT_FOUND);
    }
    public static function bookNotFound(): static
    {
        return new static('Book(s) not found', Response::NOT_FOUND);
    }
}