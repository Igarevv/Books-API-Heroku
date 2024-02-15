<?php

namespace App\Http\Model;

use App\App;
use App\Core\Database\DatabaseInterface;
use App\Core\Database\Insert\InsertQueryBuilder;

abstract class Model
{
    protected DatabaseInterface $db;

    public function __construct()
    {
        $this->db = App::db();
    }

}