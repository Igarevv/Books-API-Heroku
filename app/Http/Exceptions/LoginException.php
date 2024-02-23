<?php

namespace App\Http\Exceptions;

use App\Core\Http\Response\Response;

class LoginException extends \Exception
{
    public static function invalidInputFields(): static
    {
        return new static('Fields email and password required!', Response::BAD_REQUEST);
    }

    public static function wrongPassword(): static
    {
        return new static('Wrong password or email', Response::OK);
    }

    public static function unauthorized(): static
    {
        return new static('Unauthorized request. Please login', Response::UNAUTHORIZED);
    }
}