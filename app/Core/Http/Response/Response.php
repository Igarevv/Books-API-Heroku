<?php

namespace App\Core\Http\Response;

class Response implements ResponseInterface
{
    private string $message = 'Something wrong';
    private int $status = 404;
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
          'message' => $this->message,
        ]);
        exit;
    }
    public function get(): array
    {
        http_response_code($this->status);
        return [
          $this->status,
          $this->message
        ];
    }
}