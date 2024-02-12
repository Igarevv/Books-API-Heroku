<?php

namespace App\Core\Http\Request;

interface JsonRequestInterface
{
    public function getJsonData(string $key = ''): string|array;
    public function setJsonData(array $jsonData): void;
}