<?php

namespace App\Http\Model\Repository\User;

interface UserRepositoryInterface
{
    public function insert(array $userData): bool;
    public function findUserBy(string $key, string $value): array;
    public function addFavoriteBook(mixed $user_id, mixed $book_id): bool;
    public function deleteFromFavorites(mixed $user_id, int $book_id): bool;
    public function isBookInFavorites(mixed $user_id, int $book_id): bool;
}