<?php

namespace App\Http\Service\Auth;

use App\Core\Validator\ValidatorInterface;
use App\Http\Model\User\UserModel;

class RegisterService
{

    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $userData): bool
    {
        $rules = [
          'name' => ['required', 'max:255', 'alphanumeric'],
          'email' => ['email', 'unique:User'],
          'password' => ['required'],
        ];

        $registerValidationService = new RegisterValidationService();
        if ( ! $registerValidationService->checkFields($userData, $rules)) {
            return false;
        }

        $validation = $this->validator->validate($userData, $rules);
        if ( ! $validation) {
            return false;
        }
        return true;
    }

    public function insertData(array $userData): bool
    {
        return UserModel::insert($userData);
    }

    public function errors(): array
    {
        return $this->validator->errors();
    }

}