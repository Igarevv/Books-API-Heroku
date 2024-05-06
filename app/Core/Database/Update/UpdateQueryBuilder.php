<?php

namespace App\Core\Database\Update;

use App\Core\Database\AbstractQueryBuilder;

class UpdateQueryBuilder extends AbstractQueryBuilder
{
    private array $columns = [];
    private array $conditions = [];
    public function update(string $column, mixed $value): UpdateQueryBuilder
    {
        $this->columns[$column] = $value;
        return $this;
    }
    public function where(string $column, string $operator, string $prepareColumn): UpdateQueryBuilder
    {
        $this->conditions[] = [$column, $operator, " :".$prepareColumn];
        return $this;
    }
    public function getQuery(): string
    {
        $table = self::getTable();
        $query = "UPDATE {$table} SET ";
        foreach ($this->columns as $column => $value){
            $query .= "{$column} = '{$value}', ";
        }
        $query = rtrim($query, ', ');
        if($this->conditions){
            $query .= " WHERE ";
            foreach ($this->conditions as $condition){
                $query .= $condition[0] . " " . $condition[1] . $condition[2] . " AND ";
            }
            $query = rtrim($query, " AND ");
        }
        return $query;
    }
}