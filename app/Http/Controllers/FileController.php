<?php

namespace App\Http\Controllers;


use App\Core\Http\Response\JsonResponse;
use App\Http\Exceptions\NotFoundException;
use App\Http\Exceptions\ServerException;
use App\Http\Service\FileService;

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