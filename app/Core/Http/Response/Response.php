<?php

namespace App\Core\Http\Response;

class Response implements ResponseInterface
{

    private string $message = 'Something wrong';

    private array $data = [];

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

    public function data(mixed $data): Response
    {
        $this->data = is_array($data) ? $data : func_get_args();
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
            $response['data'] = $this->data;
        }
        echo json_encode($response);
        exit;
    }
}