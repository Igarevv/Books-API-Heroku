<?php

namespace App\Http\Controllers;

use docker\app\Core\Http\Response\JsonResponse;
use docker\app\Core\Http\Response\Response;
use docker\app\Http\Exceptions\NotFoundException;
use docker\app\Http\Exceptions\ServerException;
use docker\app\Http\Service\FileService;

class FileController
{
    public function __construct(
      private readonly FileService $service
    ) {}

    public function saveBooksInCSV(): JsonResponse
    {
        try {
            $this->service->saveCSV();

            return new JsonResponse(Response::OK, "CSV file was saved in project root folder as 'books.csv'");
        } catch (NotFoundException|ServerException $e){
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }
}