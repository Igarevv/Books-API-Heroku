<?php

namespace App\Http\Service;

use Booksuse docker\app\Core\Http\Response\Response;
use docker\app\Core\Validator\ValidatorInterface;
use docker\app\Http\Exceptions\ServerException;
use docker\app\Http\Exceptions\DataException;
use docker\app\Http\Exceptions\NotFoundException;
use docker\app\Http\Model\DTO\Book;
use docker\app\Http\Model\Repository\Book\BookRepositoryInterface;

class BookService
{
    protected array $bookData = [];

    public function __construct(
      private readonly ValidatorInterface $validator,
      private readonly BookRepositoryInterface $repository
    ) {}

    /**
     * @throws docker\app\Http\Exceptions\NotFoundException
     */
    public function showAllBooks(?string $limitOffset): array
    {
        $limit = 0;
        $offset = 0;
        $values = $this->validateRange($limitOffset);

        if ($values) {
            if (isset($values[1])) {
                [$offset, $limit] = $values;
            } else {
                $limit = $values[0];
            }
        }

        $books = $this->repository->findBooks($offset, $limit);

        if(! $books){
            throw NotFoundException::bookNotFound();
        }
        return $books;
    }

    /**
     * @throws docker\app\Http\Exceptions\NotFoundException
     */
    public function showOneBook(mixed $book_id): array
    {
        $book = $this->repository->findBooks(book_id: $book_id);
        if(! $book){
            throw NotFoundException::bookNotFound();
        }
        return $book;
    }
    /**
     * @throws docker\app\Http\Exceptions\ServerException
     */
    public function store(Book $bookDto): void
    {
        $result = $this->repository->insertBook($bookDto);
        if(is_string($result)){
            throw new ServerException($result, Response::SERVER_ERROR);
        }
    }

    /**
     * @throws docker\app\Http\Exceptions\NotFoundException
     */
    public function delete(mixed $bookId): void
    {
        if (!is_numeric($bookId)){
            throw NotFoundException::bookNotFound();
        }

        $result = $this->repository->deleteBook($bookId);

        if(! $result){
            throw NotFoundException::bookNotFound();
        }
    }
    public function bookDto(): Book
    {
        return new Book($this->bookData['title'],
          (array) $this->bookData['author'],
          $this->bookData['year'],
          (array) $this->bookData['genre'],
          $this->bookData['description'], $this->bookData['isbn']);
    }

    /**
     * @throws docker\app\Http\Exceptions\DataException
     */
    public function validate(array $data): bool
    {
        $rules = [
          'title'       => ['required'],
          'year'        => ['digits:4'],
          'author'      => ['required'],
          'genre'       => ['required'],
          'isbn'        => ['digits:13', "unique:\"Book\""],
          'description' => ['required'],
        ];

        if (! FieldValidationService::checkFields($data, $rules)) {
            throw DataException::unprocessable();
        }

        $validate = $this->validator->validate($data, $rules);
        if ($validate) {
            $this->bookData = $data;
            return true;
        }
        return false;
    }
    public function errors(): array
    {
        return $this->validator->errors();
    }

    protected function validateRange(?string $input): array|false
    {
        if(! $input){
            return false;
        }
        $values = explode(',', $input);
        foreach ($values as $value){
            if (! is_numeric($value) || $value < 0){
               return false;
            }
        }
        return $values;
    }
}