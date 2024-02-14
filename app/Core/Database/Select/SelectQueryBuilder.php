<?php

namespace App\Core\Database\Select;

use App\Core\Database\AbstractQueryBuilder;

class SelectQueryBuilder extends AbstractQueryBuilder implements SelectInterface
{
    protected array $columns = [];
    protected array $conditions = [];
    protected array $joiner = [];
    public function select($columns): SelectQueryBuilder
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();
        return $this;
    }
    public function where(string $column, string $operator, mixed $value): SelectQueryBuilder
    {
        $this->conditions[] = [$column, $operator, " :".$value];
        return $this;
    }

    public function join(
      string $joinTable,
      string $parentColumn,
      string $operator,
      string $joinColumn,
      string $joinType = 'INNER'
    ): SelectQueryBuilder
    {
        $this->joiner = [
          'type'     => $joinType,
          'table'         => $joinTable,
          'column1' => $parentColumn,
          'operator'     => $operator,
          'column2'   => $joinColumn,
        ];
        return $this;
    }
    public function getQuery(): string
    {
        $query = "SELECT " . implode(', ', $this->columns) . " FROM " . $this->table;
        if(!empty($this->alias)){
            $query .= " AS {$this->alias}";
        }
        if(! empty($this->joiner)){
            $type = $this->joiner['type'];
            $table = $this->joiner['table'];
            $p_column1 = $this->joiner['column1']; //parent column
            $operator = $this->joiner['operator'];
            $c_column2 = $this->joiner['column2']; // child

            if($this->alias !== ''){
                $query .= " {$type} JOIN {$table} ON {$this->alias}.{$p_column1} {$operator} {$table}.{$c_column2}";
            } else{
                $query .= " {$type} JOIN {$table} ON {$p_column1} {$operator} {$table}.{$c_column2}";
            }
        }
        if(! empty($this->conditions)){
            $query .= " WHERE ";
            foreach ($this->conditions as $condition) {
                $query .= $condition[0] . " " . $condition[1] . $condition[2] . " AND ";
            }
            $query = rtrim($query, " AND ");
        }
        return $query;
    }
}