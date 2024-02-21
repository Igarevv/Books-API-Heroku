<?php

namespace App\Core\Http\Response;

class JsonResponse extends Response implements ResponseInterface
{
    private int $status;

    private array $body;

    public function __construct($status, array $body = [])
    {
        $this->status = $status;
        $this->body = $body;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->status);
        header("Content-Type: application/json");

        if(! $this->body){
            $response = [
              'status' => $this->status,
              'message' => self::HTTP_STATUS_MESSAGE[$this->status],
            ];
        }else{
            $response = [
              'status' => $this->status,
              'message' => self::HTTP_STATUS_MESSAGE[$this->status],
              'data' => $this->body
            ];
        }

        echo json_encode($response);
        exit;
    }
}