<?php

namespace App\Core\Database;

use PDO;

interface DatabaseInterface
{
    public function prepare(string $sql): \PDOStatement;
    public function execute(string $sql, array $parameters): \PDOStatement|false;
}