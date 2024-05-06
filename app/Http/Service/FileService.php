<?php

namespace App\Http\Service;

use docker\app\Core\Http\Response\Response;
use docker\app\Http\Exceptions\NotFoundException;
use docker\app\Http\Exceptions\ServerException;
use docker\app\Http\Model\Repository\Book\BookRepository;

class FileService
{

    public function __construct(
      protected readonly BookRepository $repository
    ) {}

    /**
     * @throws docker\app\Http\Exceptions\NotFoundException
     * @throws docker\app\Http\Exceptions\ServerException
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