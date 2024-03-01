<?php
namespace App\Http\Exceptions;

use App\Core\Http\Response\Response;

class DataException extends \Exception
{

    public static function unprocessable(): static
    {
        return new static('Please, enter all required fields or check the spelling of the fields name.', Response::UNPROCESSABLE);
    }
}