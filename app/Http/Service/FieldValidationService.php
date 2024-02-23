<?php

namespace App\Http\Service;

class FieldValidationService
{
    public function checkFields(array $userData, array $rules): bool
    {
        if(array_is_list($rules)){
            $rules = array_flip($rules);
        }
        return !array_diff_key($rules, $userData);
    }
}