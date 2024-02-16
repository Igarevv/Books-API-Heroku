<?php

namespace App\Core\Http\Response;

interface ResponseInterface
{
    public function status($status): Response;
    public function message(string $message): Response;
    public function data(mixed $data, bool $errors): Response;
    public function send(): void;
    public function notFound(): void;
    public const CREATED     = 201;
    public const MOVED       = 301;
    public const BAD_REQUEST = 400;
    public const NOT_ALLOWED = 401;
    public const FORBIDDEN   = 403;
    public const NOT_FOUND   = 404;
    public const SERVER_ERR  = 500;
    public const UNPROCESSABLE = 422;
}