<?php

namespace App\Core\Http\Response;

interface ResponseInterface
{
    public function status($status): Response;
    public function message(string $message): Response;
    public function send(): void;
}