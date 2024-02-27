<?php

namespace App\Http\Service;

use App\Core\Http\Response\Response;
use App\Core\Validator\ValidatorInterface;
use App\Http\Exceptions\BookException;
use App\Http\Exceptions\DataException;
use App\Http\Model\DTO\Book;
use App\Http\Model\Repository\Book\BookRepositoryInterface;

class BookService
{
    protected array $bookData = [];

    public function __construct(
      private readonly ValidatorInterface $validator,
      private readonly BookRepositoryInterface $repository
    ) {}

    /**
     * @throws \App\Http\Exceptions\BookException
     */
    public function store(Book $bookDto)
    {
        $result = $this->repository->insertBook($bookDto);
        if(! $result){
            throw new BookException('The book has not been added through server error', Response::SERVER_ERROR);
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
     * @throws \App\Http\Exceptions\DataException
     */
    public function validate(array $data): bool
    {
        $rules = [
          'title'       => ['required', 'alphanumeric'],
          'year'        => ['numeric'],
          'author'      => ['required'],
          'genre'       => ['required'],
          'isbn'        => ['digits:13', 'unique:Book'],
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

}