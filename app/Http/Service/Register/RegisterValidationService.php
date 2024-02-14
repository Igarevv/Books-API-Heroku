<?php

namespace App\Http\Service\Register;

use App\Core\Http\Request\RequestInterface;

class RegisterValidationService
{
    public function checkFields(array $userData, array $rules): bool
    {
        if($this->isValidFieldsName($userData, $rules)){
            return true;
        }
        return false;
    }
    private function isValidFieldsName(array $userData, array $rules): bool
    {
        $checkKey = function ($key) use ($rules) {
            if (array_key_exists($key, $rules)) {
                return true;
            }
            return false;
        };
        $check = array_map($checkKey, array_keys($userData));
        return ! in_array(false, $check);
    }
}