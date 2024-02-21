<?php

namespace App\Http\Model\DTO;

class Tokens
{
    public function __construct(
      private string $accessToken,
      private string $refreshToken
    ) {}

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

}