<?php

namespace App\Core\Database\Insert;

use App\Core\Database\AbstractQueryBuilder;

class InsertQueryBuilder extends AbstractQueryBuilder
{
    protected array $values = [];

    public function values(array $values): InsertQueryBuilder
    {
        $this->values = $values;
        return $this;
    }

    public function getQuery(): string
    {
        if ( ! $this->values) {
            throw new \InvalidArgumentException("Table name and values are required.");
        }

        $table = self::getTable();
        $columns = implode(', ', $this->values);
        $placeholder = implode(', ', array_map(fn($key) => ":{$key}", $this->values));
        return "INSERT INTO {$table}({$columns}) VALUES({$placeholder})";
    }

}