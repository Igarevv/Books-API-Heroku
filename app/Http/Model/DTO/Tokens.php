<?php

namespace App\Http\Model\DTO;

class Tokens
{
    public function __construct(
      private string $refreshToken,
      private int $user_id,
      private int $expires_in
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