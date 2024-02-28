<?php

namespace App\Http\Model\Repository\Book;

use App\Http\Model\DTO\Book;

interface BookRepositoryInterface
{
    public function findBooks(int $limit, int $offset, ?int $book_id): array;
    public function insertBook(Book $bookData): bool|string;
    public function deleteBook(mixed $bookId): bool;
}