<?php

namespace App\Core\Database;

use PDO;

interface DatabaseInterface
{
    public function prepare(string $sql): \PDOStatement;
    public function execute(string $sql, array $parameters): \PDOStatement|false;
    public function lastInsertedId(): false|string;
    public function beginTransaction(): void;
    public function commitTransaction(): void;
    public function rollBack(): void;
}