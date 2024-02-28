<?php

namespace App\Http\Service\Auth;

use App\Core\Validator\ValidatorInterface;
use App\Http\Exceptions\DataException;
use App\Http\Model\Repository\User\UserRepositoryInterface;
use App\Http\Service\FieldValidationService;
use Symfony\Component\VarDumper\Cloner\Data;

class RegisterService
{

    private ValidatorInterface $validator;
    private UserRepositoryInterface $repository;

    public function __construct(
      ValidatorInterface $validator,
      UserRepositoryInterface $repository
    )
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @throws \App\Http\Exceptions\DataException
     */
    public function validate(array $userData): bool
    {
        $rules = [
          'name' => ['required', 'max:255', 'alphanumeric'],
          'email' => ['email', 'unique:User'],
          'password' => ['required', 'min:8'],
        ];

        $validateFields = FieldValidationService::checkFields($userData, $rules);

        if (! $validateFields) {
           throw DataException::unprocessable();
        }

        $validation = $this->validator->validate($userData, $rules);
        if (! $validation) {
            return false;
        }
        return true;
    }
    public function createUser(array $userData): bool
    {
        return $this->repository->insert($userData);
    }
    public function errors(): array
    {
        return $this->validator->errors();
    }

}