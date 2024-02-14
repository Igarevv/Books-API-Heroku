<?php

namespace App\Core\Database\Select;

interface SelectInterface
{
    public function select($columns): SelectQueryBuilder;
    public function where(string $column, string $operator, mixed $value);
    public function join(
      string $joinTable,
      string $parentColumn,
      string $operator,
      string $joinColumn,
      string $joinType = 'INNER'
    ): SelectQueryBuilder;
}