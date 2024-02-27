<?php

namespace App\Http\Controllers;

use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\JsonResponse;
use App\Core\Http\Response\Response;
use App\Http\Exceptions\BookException;
use App\Http\Exceptions\DataException;
use App\Http\Service\BookService;

class BookController
{

    public function __construct(
      protected BookService $bookService
    ) {}

    public function create(RequestInterface $request): JsonResponse
    {
        $bookData = $request->input();

        try {
            if (! $this->bookService->validate($bookData)) {
                return new JsonResponse(Response::BAD_REQUEST,
                $this->bookService->errors());
            }
            $bookDto = $this->bookService->bookDto($bookData);

            $this->bookService->store($bookDto);

            return new JsonResponse(Response::CREATED);
        } catch (DataException|BookException $e) {
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }

}