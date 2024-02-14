<?php

namespace App\Core\Database;


abstract class AbstractQueryBuilder
{
    protected string $table;
    protected string $alias;
    protected readonly DatabaseInterface $database;

    public function table(string $table, string $alias = ''): static
    {
        $this->table = $table;
        $this->alias = $alias;
        return $this;
    }

    abstract public function getQuery(): string;
}