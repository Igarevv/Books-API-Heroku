<?php

namespace App\Core\Database\Select;

use docker\app\Core\Database\AbstractQueryBuilder;

class SelectQueryBuilder extends AbstractQueryBuilder
{
    protected array $columns = [];
    protected array $conditions = [];
    protected array $joiner = [];
    protected array $groupBy = [];
    protected int $offset = 0;
    protected int $limit = 0;
    public function select(...$columns): docker\app\Core\Database\Select\SelectQueryBuilder
    {
        $this->columns = $columns;
        return $this;
    }
    public function where(string $column, string $operator, string $prepareColumn): docker\app\Core\Database\Select\SelectQueryBuilder
    {
        $this->conditions[] = [$column, $operator, " :".$prepareColumn];
        return $this;
    }

    public function join(
      string $joinTable,
      string $parentColumn,
      string $operator,
      string $joinColumn,
      string $joinType = ''
    ): docker\app\Core\Database\Select\SelectQueryBuilder
    {
        $this->joiner[] = [
          'type'     => $joinType,
          'table'         => $joinTable,
          'column1' => $parentColumn,
          'operator'     => $operator,
          'column2'   => $joinColumn,
        ];
        return $this;
    }
    public function groupBy(...$columns): docker\app\Core\Database\Select\SelectQueryBuilder
    {
        $this->groupBy = $columns;
        return $this;
    }
    public function limit(int $limit, int $offset = 0): docker\app\Core\Database\Select\SelectQueryBuilder
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }
    public function getQuery(): string
    {
        $selectTable = self::getTable();
        $alias = self::getAlias();

        $query = "SELECT " . implode(', ', $this->columns) . " FROM " . $selectTable;

        if($alias){
            $query .= " AS {$alias}";
        }

        if($this->joiner){
            foreach ($this->joiner as $joiner){
                $type = $joiner['type'];
                $table = $joiner['table'];
                $p_column1 = $joiner['column1']; //parent column
                $operator = $joiner['operator'];
                $c_column2 = $joiner['column2']; // child

                $query .= " {$type} JOIN {$table} ON {$table}.{$p_column1} {$operator} {$c_column2}";
            }
        }
        if($this->conditions){
            $query .= " WHERE ";
            foreach ($this->conditions as $condition) {
                $query .= $condition[0] . " " . $condition[1] . $condition[2] . " AND ";
            }
            $query = rtrim($query, " AND ");
        }
        if($this->groupBy){
            $query .= " GROUP BY " . implode(', ', $this->groupBy);
        }
        if($this->limit !== 0){
            $query .= " LIMIT {$this->offset}, {$this->limit}";
        }
        return $query;
    }
}