<?php

namespace App\Http\Service\Auth;

use App\DTO\UserDTO;
use App\Http\Model\User\UserModel;

class LoginService
{

    private readonly array $userData;

    public function login(UserDTO $userDto, string $password): bool
    {
        if(password_verify($password, $this->userData['password'])){
            return true;
        }
        return false;
    }

    public function getUserDTO(array $data): UserDTO|false
    {
        $this->userData = $this->getUserData($data['email']);

        if (! $this->userData) {
            return false;
        }

        return new UserDTO(
          $this->userData['name'],
          $this->userData['email'],
          $this->userData['uuid']
        );
    }

    private function getUserData(string $value): array
    {
        UserModel::initialize();
        return UserModel::findUserBy('email', $value);
    }

}