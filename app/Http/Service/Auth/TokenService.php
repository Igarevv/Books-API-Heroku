<?php

namespace App\Http\Service\Auth;

use App\Http\Model\DTO\Tokens;
use App\Http\Model\Repository\Token\TokenRepositoryInterface;

class TokenService
{
    public function __construct(
      private readonly TokenRepositoryInterface $tokenRepository
    )
    {}

    public function generateTokens(array $data): array
    {
        $payload = [
          'iat'  => time(),
          'exp'  => time() + 900,
          'data' => $data,
        ];
        $accessToken = JWT::encode($payload, $_ENV['JWTKEY'], 'HS256');
        $refresh = $this->generateRefresh();
        return [
          'accessToken' => $accessToken,
          'refreshToken' => $refresh,
        ];
    }

    public function findToken(string $refreshToken): Tokens|false
    {
        $token = $this->tokenRepository->findToken($refreshToken);
        if(! $token || password_verify($refreshToken, $token['refresh_token'])){
            return false;
        }

        return new Tokens($token['refresh_token'], $token['user_id'], $token['expires_in']);
    }

    public function saveToken(string $userId, string $refreshToken, int $expiresIn): bool
    {
        return $this->tokenRepository->saveToken($userId, $refreshToken, $expiresIn);
    }

    public function isTokenTimeExpires(int $expireTime): bool
    {
        return time() > $expireTime;
    }
    public function deleteToken(string $refreshToken): bool
    {
        return $this->tokenRepository->deleteRefresh($refreshToken);
    }
    private function generateRefresh(): string
    {
        return bin2hex(random_bytes(32));
    }
}