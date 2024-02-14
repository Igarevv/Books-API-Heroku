<?php

namespace App\Core\Database;

interface DatabaseInterface
{
    public function connect(): void;
}