<?php

namespace App\Http\Service\Auth;

use App\Http\Model\DTO\Tokens;
use App\Http\Model\Repository\Token\TokenRepositoryInterface;
use Firebase\JWT\JWT;

class TokenService
{
    private string $key = 'my-secret-key'; // в .env в будущем
    public function __construct(
      private TokenRepositoryInterface $tokenRepository
    )
    {}

    public function generateTokens(...$payload): array
    {
        $data = [];
        foreach ($payload as $value){
            $data['data'] = $value;
        }
        $payload = [
          'iss'  => 'http://api.books.com',
          'iat'  => time(),
          'exp'  => time() + 900,
          'data' => $data,
        ];
        $accessToken = JWT::encode($payload, $this->key, 'HS256');
        $refresh = $this->generateRefresh();
        return [
          'accessToken' => $accessToken,
          'refreshToken' => $refresh,
        ];
    }

    public function findToken(string $refreshToken): Tokens|false
    {
        $token = $this->tokenRepository->findToken($refreshToken);
        if(! $token){
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
        $current = time();
        return $current > $expireTime;
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