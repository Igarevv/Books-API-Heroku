<?php

namespace App\Http\Exceptions;


use docker\app\Core\Http\Response\Response;

class UserException extends \Exception
{
    public static function bookInFavorite(): static
    {
        return new static('This book is already in your favorites', Response::BAD_REQUEST);
    }
}