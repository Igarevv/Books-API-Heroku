<?php

namespace App\Http\Model;

use App\App;
use App\Core\Database\DatabaseInterface;
use App\Core\Database\Insert\InsertQueryBuilder;
use App\Core\Database\Select\SelectQueryBuilder;

abstract class Model
{
    protected static DatabaseInterface $db;
    protected static string $table = '';
    protected static SelectQueryBuilder $select_builder;
    protected static InsertQueryBuilder $insert_builder;
    public static function initialize(): void
    {
        self::$db = App::db();
        self::$select_builder = new SelectQueryBuilder();
        self::$insert_builder = new InsertQueryBuilder();
    }
    protected static function getTable(): string
    {
        return static::$table;
    }
}