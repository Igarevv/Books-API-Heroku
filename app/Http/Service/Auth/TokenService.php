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

    public function generateTokens(...$payload): Tokens
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
        return new Tokens($accessToken, $refresh);
    }

    public function findToken(string $refreshToken): array
    {
        return $this->tokenRepository->findToken($refreshToken);
    }

    public function saveToken(string $userId, string $refreshToken, int $expiresIn): bool
    {
        $token = $this->findToken($refreshToken);
        if($token){
            return $this->tokenRepository->updateRefreshToken($refreshToken, $userId);
        }
        return $this->tokenRepository->saveToken($userId, $refreshToken, $expiresIn);
    }

    private function generateRefresh(): string
    {
        return bin2hex(random_bytes(32));
    }
}