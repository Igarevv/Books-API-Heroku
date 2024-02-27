<?php

namespace App\Http\Model\Repository\Book;

use App\Http\Model\DTO\Book;

interface BookRepositoryInterface
{
    public function insertBook(Book $bookData): bool;
}