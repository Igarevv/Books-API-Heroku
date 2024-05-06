<?php

namespace App\Http\Service\Auth;

use docker\app\Core\Cookie\Cookie;
use docker\app\Http\Exceptions\LoginException;
use docker\app\Http\Exceptions\NotFoundException;
use docker\app\Http\Model\DTO\User;
use docker\app\Http\Model\Repository\User\UserRepositoryInterface;
use docker\app\Http\Service\FieldValidationService;
use Books

class LoginService
{

    private array $userData = [];

    public function __construct(
      private readonly UserRepositoryInterface $repository,
      private readonly TokenService $tokenService,
    ) {}

    /**
     * @throws docker\app\Http\Exceptions\LoginException
     */
    public function login(User $userDto, string $password): array
    {
        if (! password_verify($password, $this->userData['password'])) {
            throw LoginException::wrongPassword();
        }

        $userId = $userDto->getUserId();

        $data = [
          'user_id' => $userDto->getUserId(),
          'role'    => $userDto->getRole()
        ];
        $tokens = $this->tokenService->generateTokens($data);

        $this->tokenService->saveToken($userId, $tokens['refreshToken'],
          time() + $_ENV['REFRESH_LIFE_TIME']);

        Cookie::set('_logid', $tokens['refreshToken'],
          $_ENV['REFRESH_LIFE_TIME'], '/api/auth');

        return $tokens;
    }

    /**
     * @throws docker\app\Http\Exceptions\LoginException
     */
    public function refresh(string $refreshToken): array
    {
        $tokenFromDb = $this->tokenService->findToken($refreshToken);

        if (! $tokenFromDb) {
            throw LoginException::unauthorized();
        }

        $this->tokenService->deleteToken($tokenFromDb->getRefreshToken());

        if ($this->tokenService->isTokenTimeExpires($tokenFromDb->getExpiresIn())) {
            throw LoginException::unauthorized();
        }

        $userDto = $this->findUserById($tokenFromDb->getUserId());
        $data = [
          'user_id' => $userDto->getUserId(),
          'role'    => $userDto->getRole()
        ];
        $tokens = $this->tokenService->generateTokens($data);

        $this->tokenService->saveToken($userDto->getUserId(),
          $tokens['refreshToken'], time() + $_ENV['REFRESH_LIFE_TIME']);

        Cookie::set('_logid', $tokens['refreshToken'],
          $_ENV['REFRESH_LIFE_TIME'], '/api/auth');
        return $tokens;
    }

    public function logout(string $refreshToken): bool
    {
        Cookie::delete('_logid');
        return $this->tokenService->deleteToken($refreshToken);
    }

    public function isInputFormatValid(array $data): bool
    {
        if (! $data) {
            return false;
        }

        $validateField = FieldValidationService::checkFields($data, ['email', 'password']);

        if ($validateField) {
            return true;
        }
        return false;
    }

    public function findUserById(int $id): User
    {
        $user = $this->repository->findUserBy('id', $id);
        $role = $user['is_admin'] === 0 ? 'user' : 'admin';

        return new User($user['id'], $user['name'], $user['email'], $role);
    }

    /**
     * @throws docker\app\Http\Exceptions\LoginException
     */
    public function getUserByEmail(array $data): User
    {
        if (! $this->isInputFormatValid($data)) {
            throw LoginException::invalidInputFields();
        }

        $this->userData = $this->repository->findUserBy('email',
          $data['email']);

        if (! $this->userData) {
            throw LoginException::wrongPassword();
        }
        $role = $this->userData['is_admin'] === 0 ? 'user' : 'admin';

        return new User(
          $this->userData['id'],
          $this->userData['name'],
          $this->userData['email'],
          $role
        );
    }

}