<?php

namespace App\Http\Service;

use App\Http\Exceptions\NotFoundException;
use App\Http\Exceptions\UserException;
use App\Http\Model\Repository\Book\BookRepositoryInterface;
use App\Http\Model\Repository\User\UserRepositoryInterface;

class UserService
{
    public function __construct(
      protected readonly UserRepositoryInterface $user,
      protected readonly BookRepositoryInterface $book
    ) {}

    /**
     * @throws \App\Http\Exceptions\NotFoundException
     * @throws \App\Http\Exceptions\UserException
     */
    public function addBook(mixed $user_id, mixed $book_id): void
    {
        if (! is_numeric($book_id)){
            throw NotFoundException::bookNotFound();
        }

        if(! $this->book->isBookExists($book_id)){
            throw NotFoundException::bookNotFound();
        }

        if($this->user->isBookInFavorites($user_id, $book_id)){
            throw UserException::bookInFavorite();
        }

        $this->user->addFavoriteBook($user_id, $book_id);
    }

    /**
     * @throws \App\Http\Exceptions\NotFoundException
     */
    public function deleteBook(mixed $user_id, mixed $book_id): void
    {
        if (! is_numeric($book_id)){
            throw NotFoundException::bookNotFound();
        }

        $result = $this->user->deleteFromFavorites($user_id, $book_id);

        if(! $result){
            throw NotFoundException::bookNotFound();
        }
    }

    /**
     * @throws \App\Http\Exceptions\NotFoundException
     */
    public function showAllFavoriteBooks(mixed $user_id): array
    {
        $books = $this->book->findBooks(user_id: $user_id);

        if(! $books){
            throw NotFoundException::bookNotFound();
        }
        return $books;
    }

    /**
     * @throws \App\Http\Exceptions\NotFoundException
     */
    public function showOneFavoriteBook(mixed $user_id, mixed $book_id): array
    {
        if(! is_numeric($book_id)){
            throw NotFoundException::bookNotFound();
        }
        $book = $this->book->findBooks(book_id: $book_id, user_id: $user_id);

        if(! $book){
            throw NotFoundException::bookNotFound();
        }
        return $book;
    }
}