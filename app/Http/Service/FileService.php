<?php

namespace App\Http\Service;

use App\Core\Http\Response\Response;
use App\Http\Exceptions\NotFoundException;
use App\Http\Exceptions\ServerException;
use App\Http\Model\Repository\Book\BookRepository;

class FileService
{

    public function __construct(
      protected readonly BookRepository $repository
    ) {}

    /**
     * @throws \App\Http\Exceptions\NotFoundException
     * @throws \App\Http\Exceptions\ServerException
     */
    public function saveCSV(): void
    {
        $books = $this->repository->findBooks();

        if ( ! $books) {
            throw NotFoundException::bookNotFound();
        }

        $file = fopen(APP_PATH . '/books.csv', 'w') or throw new ServerException('Error opening file',
          Response::SERVER_ERROR);

        foreach ($books as $book) {
            fputcsv($file, $book);
        }

        fclose($file);
    }

}