<?php

namespace App\Http\Service\Auth;

use App\Core\Http\Response\JsonResponse;
use App\Core\Http\Response\Response;
use App\Http\Exceptions\LoginException;
use App\Http\Exceptions\UserNotFoundException;
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

    /**
     * @throws \App\Http\Exceptions\LoginException
     */
    public function login(User $userDto, string $password): array
    {
        if ( ! password_verify($password, $this->userData['password'])) {
            throw LoginException::wrongPassword();
        }
        $userId = $userDto->getUserId();
        $tokens = $this->tokenService->generateTokens($userId,
          $userDto->getRole());

        $this->tokenService->saveToken($userId, $tokens['refreshToken'],
          $_ENV['REFRESH_LIVE_TIME']);

        return $tokens;
    }

    /**
     * @throws \App\Http\Exceptions\LoginException
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

        $tokens = $this->tokenService->generateTokens($userDto->getUserId(),
          $userDto->getRole());

        $this->tokenService->saveToken($userDto->getUserId(),
          $tokens['refreshToken'], $_ENV['REFRESH_LIVE_TIME']);

        return $tokens;
    }

    public function isInputFormatValid(array $data): bool
    {
        if ( ! $data) {
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

    public function findUserById(int $id): User
    {
        $user = $this->repository->findUserBy('id', $id);
        $role = $user['is_admin'] == 0 ? 'user' : 'admin';

        return new User($user['id'], $user['name'], $user['email'], $role);
    }

    /**
     * @throws \App\Http\Exceptions\UserNotFoundException
     * @throws \App\Http\Exceptions\LoginException
     */
    public function getUserByEmail(array $data): User
    {
        if ( ! $this->isInputFormatValid($data)) {
            throw LoginException::invalidInputFields();
        }

        $this->userData = $this->repository->findUserBy('email',
          $data['email']);

        if ( ! $this->userData) {
            throw UserNotFoundException::userNotFound();
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