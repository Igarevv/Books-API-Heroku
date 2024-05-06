<?php

namespace App\Http\Service;

use docker\app\Http\Exceptions\NotFoundException;
use docker\app\Http\Exceptions\UserException;
use docker\app\Http\Model\Repository\Book\BookRepositoryInterface;
use docker\app\Http\Model\Repository\User\UserRepositoryInterface;

class UserService
{
    public function __construct(
      protected readonly UserRepositoryInterface $user,
      protected readonly BookRepositoryInterface $book
    ) {}

    /**
     * @throws docker\app\Http\Exceptions\NotFoundException
     * @throws docker\app\Http\Exceptions\UserException
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
     * @throws docker\app\Http\Exceptions\NotFoundException
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
     * @throws docker\app\Http\Exceptions\NotFoundException
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
     * @throws docker\app\Http\Exceptions\NotFoundException
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