<?php

namespace App\Http\Controllers;

use App\Core\Helpers\JWTHelper;
use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\JsonResponse;
use App\Http\Exceptions\NotFoundException;
use App\Http\Exceptions\UserException;
use App\Http\Service\UserService;
use App\Core\Http\Response\Response;

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