<?php

namespace App\Http\Service\Auth;

use App\Http\Model\DTO\User;
use App\Http\Model\Repository\User\UserRepositoryInterface;
use App\Http\Service\FieldValidationService;

class LoginService
{
    private array $userData = [];

    public function __construct(
      private UserRepositoryInterface $repository,
      private TokenService $tokenService,
    ) {}

    public function login(User $userDto, string $password): bool|array
    {
        if (password_verify($password, $this->userData['password'])) {
            $userId = $userDto->getUserId();
            $tokens = $this->tokenService->generateTokens($userId,
              $userDto->getRole());

            $refreshToken = $tokens->getRefreshToken();

            $this->tokenService->saveToken($userId, $refreshToken, time() + 604800);

            setcookie('_logid', $refreshToken, time() + 604800, path: '/api/auth', httponly: true);
            return [
              'accessToken' => $tokens->getAccessToken(),
              'refreshToken' => $refreshToken,
            ];
        }
        return false;
    }

    public function refresh(string $refreshToken): array|bool
    {
        $tokenFromDb = $this->tokenService->findToken($refreshToken);

        if(! $tokenFromDb){
            return false;
        }

        $user = $this->repository->findUserBy('id', $tokenFromDb['user_id']);
        $role = $user['is_admin'] == 0 ? 'user' : 'admin';

        $userDto = new User($user['id'], $user['name'], $user['email'], $role);
        $userId = $userDto->getUserId();

        $tokens = $this->tokenService->generateTokens($userId,
          $userDto->getRole());

        $this->tokenService->saveToken($userId, $refreshToken, time() + 604800);

        setcookie('_logid', $refreshToken, time() + 604800, path: '/api/auth', httponly: true);
        return [
          'accessToken' => $tokens->getAccessToken(),
          'refreshToken' => $refreshToken,
        ];
    }
    public function isInputFormatValid(array $data): bool
    {
        if(! $data){
            return false;
        }
        $validateField = (new FieldValidationService())->checkFields($data, [
          'email', 'password',
        ]);

        if ($validateField) {
            return true;
        }
        return false;
    }

    public function getUserByEmail(array $data): User|false
    {
        $this->userData = $this->repository->findUserBy('email',
          $data['email']);

        if ( ! $this->userData) {
            return false;
        }
        $role = $this->userData['is_admin'] == 0 ? 'user' : 'admin';

        return new User(
          $this->userData['id'],
          $this->userData['name'],
          $this->userData['email'],
          $role
        );
    }
}