<?php

namespace App\Core\Http\Request;

interface JsonRequestInterface
{
    public function setJsonData(array $jsonData): void;
}