<?php

namespace App\Core\Database\DeleteQueryBuilder;

use docker\app\Core\Database\AbstractQueryBuilder;

class DeleteQueryBuilder extends AbstractQueryBuilder
{
    protected array $conditions = [];
    public function where(string $column, string $operator, string $prepareColumn): docker\app\Core\Database\DeleteQueryBuilder\DeleteQueryBuilder
    {
        $this->conditions[] = [$column, $operator, " :".$prepareColumn];
        return $this;
    }
    public function getQuery(): string
    {
        $table = self::getTable();
        $query = "DELETE FROM {$table} WHERE";
        foreach ($this->conditions as $condition){
            $query .= " $condition[0] $condition[1] $condition[2] AND ";
        }
        return rtrim($query, " AND ");
    }
}