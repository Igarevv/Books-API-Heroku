<?php

namespace App\Core\Http\Json;

class JsonParser
{
    public static function response(array $data): void
    {
        if(!empty($data)){
            header("Content-Type: application/json");
            echo json_encode($data);
        }
    }
    public static function request(): array
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}