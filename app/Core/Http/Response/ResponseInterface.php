<?php

namespace App\Core\Http\Response;

interface ResponseInterface
{
    public function send(): void;
}