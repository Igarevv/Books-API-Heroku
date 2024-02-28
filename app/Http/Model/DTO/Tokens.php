<?php

namespace App\Http\Model\DTO;

class Tokens
{
    public function __construct(
      private readonly string $refreshToken,
      private readonly int $user_id,
      private readonly int $expires_in
    ) {}

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getExpiresIn(): int
    {
        return $this->expires_in;
    }

}