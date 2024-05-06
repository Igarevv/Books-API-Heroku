<?php

namespace App\Http\Model\Repository\Book;

use docker\app\Http\Model\DTO\Book;

interface BookRepositoryInterface
{
    public function findBooks(int $limit, int $offset, ?int $book_id, mixed $user_id): array;
    public function insertBook(Book $bookData): bool|string;
    public function deleteBook(int $bookId): bool;
    public function isBookExists(int $book_id): bool;
}