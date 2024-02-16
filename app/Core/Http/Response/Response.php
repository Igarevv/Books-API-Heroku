<?php

namespace App\Core\Http\Response;

class Response implements ResponseInterface
{

    private string $message = 'Something wrong';

    private array $data = [];
    private bool $errors = false;

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

    public function data(mixed $data, bool $errors): Response
    {
        $this->data = is_array($data) ? $data : func_get_args();
        $this->errors = $errors;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->status);
        header("Content-Type: application/json");

        $response = [
          'status' => $this->status,
        ];
        if (isset($this->message)) {
            $response['message'] = $this->message;
        }
        if (! empty($this->data)) {
            $key = $this->errors ? 'errors' : 'data';
            $response[$key] = $this->data;
        }
        echo json_encode($response);
        exit;
    }
    public function notFound(): void
    {
        http_response_code(404);
        header("Content-Type: application/json");
        echo json_encode([
          'status' => 404,
          'message' => "404 | NOT FOUND"
        ]);
    }
}