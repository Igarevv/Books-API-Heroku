<?php

namespace App\Core\Http\Json;

class JsonParser
{
    public static function request(): array
    {
        return json_decode(file_get_contents('php://input'), true, JSON_THROW_ON_ERROR);
    }
}