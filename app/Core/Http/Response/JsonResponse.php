<?php

namespace App\Core\Http\Response;

use Booksuse Booksclass JsonResponse extends Response implements ResponseInterface
{
    protected int $status;

    protected mixed $body;

    protected bool $isError;
    public function __construct(int $status, mixed $body = [], bool $isError = false)
    {
        $this->status = $status;
        $this->body = $body;
        $this->isError = $isError;
    }

    public function send(): void
    {
        http_response_code($this->status);
        header("Content-Type: application/json");

        $field = $this->isError ? 'errors' : (is_array($this->body) ? 'data' : 'message');

        if(! $this->body){
            $response = [
              'status' => $this->status,
              'HTTP-message' => self::HTTP_STATUS_MESSAGE[$this->status]
            ];
        } else{
            $response = [
              'status' => $this->status,
              'HTTP-message' => self::HTTP_STATUS_MESSAGE[$this->status],
              $field => $this->body
            ];
        }

        echo json_encode($response, JSON_THROW_ON_ERROR);
        exit;
    }
}