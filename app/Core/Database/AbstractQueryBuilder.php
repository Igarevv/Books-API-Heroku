<?php

namespace App\Core\Database;

abstract class AbstractQueryBuilder
{

    protected static string $table;

    protected static string $alias;

    protected static DatabaseInterface $database;

    public static function table(string $table, string $alias = ''): static
    {
        static::$table = $table;
        static::$alias = $alias;
        return new static();
    }

    public static function getTable(): string
    {
        return static::$table;
    }

    public static function getAlias(): string
    {
        return self::$alias;
    }

    abstract public function getQuery(): string;

}