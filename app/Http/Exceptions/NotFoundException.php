<?php

namespace App\Http\Exceptions;


use App\Core\Http\Response\Response;

class NotFoundException extends \Exception
{
    public static function userNotFound(): static
    {
        return new static('User not found', Response::NOT_FOUND);
    }
    public static function BookNotFound(): static
    {
        return new static('Book not found', Response::NOT_FOUND);
    }
}