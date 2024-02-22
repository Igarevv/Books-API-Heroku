<?php

namespace App\Http\Model\Repository\User;

interface UserRepositoryInterface
{
    public function insert(array $userData): bool;
    public function findUserBy(string $key, string $value): array;
}