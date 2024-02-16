<?php

namespace App\DTO;

final class UserDTO
{
    public function __construct(
      private readonly string $name,
      private readonly string $email,
      private readonly string $user_id
    )
    {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }

}