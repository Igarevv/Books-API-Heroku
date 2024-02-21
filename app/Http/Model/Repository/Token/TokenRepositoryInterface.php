<?php

namespace App\Http\Model\Repository\Token;

interface TokenRepositoryInterface
{
    public function saveToken(string $userId, string $refreshToken, int $expiresIn): bool;
    public function findToken(string $token): array;
    public function updateRefreshToken(string $newRefresh, int $user_id);
}