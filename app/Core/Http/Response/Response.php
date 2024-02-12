<?php

namespace App\Core\Http\Response;

class Response implements ResponseInterface
{
    private string $message;
    private int $status;
    public const CREATED     = 201;
    public const MOVED       = 301;
    public const BAD_REQUEST = 400;
    public const NOT_ALLOWED = 401;
    public const FORBIDDEN   = 403;
    public const NOT_FOUND   = 404;
    public const SERVER_ERR  = 500;
    public function status($status = self::NOT_FOUND): Response
    {
        $this->status = $status;
        return $this;
    }
    public function message(string $message): Response
    {
        $this->message = $message;
        return $this;
    }
    public function send(): void
    {
        http_response_code($this->status);
        header("Content-Type: application/json");
        echo json_encode([
          'status'  => $this->status,
          'message' => $this->message
        ]);
        exit;
    }
}