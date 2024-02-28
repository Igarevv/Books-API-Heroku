<?php

namespace App\Http\Controllers;

use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\JsonResponse;
use App\Core\Http\Response\Response;
use App\Http\Exceptions\BookException;
use App\Http\Exceptions\ServerException;
use App\Http\Exceptions\DataException;
use App\Http\Exceptions\NotFoundException;
use App\Http\Service\BookService;

class BookController
{
    public function __construct(
      protected BookService $bookService
    ) {}

    public function index(RequestInterface $request): JsonResponse
    {
        $limitOffset = $request->input('limit');
        try {
            $books = $this->bookService->showAllBooks($limitOffset);
        } catch (NotFoundException $e){
            return new JsonResponse($e->getCode(), $e->getMessage());
        }

        return new JsonResponse(Response::OK, $books);
    }

    public function create(RequestInterface $request): JsonResponse
    {
        $bookData = $request->input();
        try {
            if (! $this->bookService->validate($bookData)) {
                return new JsonResponse(Response::BAD_REQUEST,
                $this->bookService->errors());
            }
            $bookDto = $this->bookService->bookDto();

            $this->bookService->store($bookDto);

            return new JsonResponse(Response::CREATED);
        } catch (DataException|ServerException $e) {
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }

    public function showOneBook(RequestInterface $request, mixed $bookId): JsonResponse
    {
        try {
            $book = $this->bookService->showOneBook($bookId);

            return new JsonResponse(Response::OK, $book);
        } catch (NotFoundException $e){
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }

    public function deleteBook(RequestInterface $request, mixed $bookId): JsonResponse
    {
        try {
            $this->bookService->delete($bookId);

            return new JsonResponse(Response::OK, 'The book was deleted successfully.');
        } catch (NotFoundException $e){
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }
}