<?php

namespace App\Http\Model\DTO;

class User
{
    public function __construct(
      private readonly string $user_id,
      private readonly string $name,
      private readonly string $email,
      private readonly string $role
    )
    {
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

}