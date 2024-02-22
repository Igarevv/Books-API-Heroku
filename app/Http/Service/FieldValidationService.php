<?php

namespace App\Http\Service;

class FieldValidationService
{

    public function checkFields(array $userData, array $rules): bool
    {
        if ($this->isValidFieldsName($userData, $rules)) {
            return true;
        }
        return false;
    }

    private function isValidFieldsName(array $userData, array $rules): bool
    {
        $checkKey = function ($key) use ($rules) {
            if (array_is_list($rules)) {
                return in_array($key, $rules);
            }
            return array_key_exists($key, $rules);
        };

        $check = array_map($checkKey, array_keys($userData));
        return ! in_array(false, $check);
    }

}