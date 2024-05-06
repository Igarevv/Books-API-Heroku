<?php

namespace App\Http\Controllers;

use docker\app\Core\Helpers\JWTHelper;
use docker\app\Core\Http\Request\RequestInterface;
use docker\app\Core\Http\Response\JsonResponse;
use docker\app\Http\Exceptions\NotFoundException;
use docker\app\Http\Exceptions\UserException;
use docker\app\Http\Service\UserService;
use docker\app\Core\Http\Response\Response;

class UserController
{
    public function __construct(
      private readonly UserService $service
    ) {}

    public function index(RequestInterface $request): JsonResponse
    {
        $userId = JWTHelper::validateAccessToken($request)->data->user_id;

        try {
            $books = $this->service->showAllFavoriteBooks($userId);

            return new JsonResponse(Response::OK, $books);
        } catch (NotFoundException $e){
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }

    public function showOneBook(RequestInterface $request, mixed $book_id): JsonResponse
    {
        $userId = JWTHelper::validateAccessToken($request)->data->user_id;

        try {
            $book = $this->service->showOneFavoriteBook($book_id, $userId);

            return new JsonResponse(Response::OK, $book);
        } catch (NotFoundException $e){
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }
    public function addUserFavoriteBook(RequestInterface $request, mixed $book_id): JsonResponse
    {
        $userId = JWTHelper::validateAccessToken($request)->data->user_id;

        try {
            $this->service->addBook($userId, $book_id);

            return new JsonResponse(Response::OK, 'Success');
        } catch (NotFoundException|UserException $e){
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }

    public function deleteFavoriteBook(RequestInterface $request, mixed $book_id): JsonResponse
    {
        $user_id = JWTHelper::validateAccessToken($request)->data->user_id;

        try {
            $this->service->deleteBook($user_id, $book_id);

            return new JsonResponse(Response::OK, 'The book has been removed from your favorites');
        } catch (NotFoundException $e){
            return new JsonResponse($e->getCode(), $e->getMessage());
        }
    }
}