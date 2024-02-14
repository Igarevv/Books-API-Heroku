<?php

namespace App\Http\Model;

use App\Core\Database\Select\SelectQueryBuilder;


class UserModel
{
    public static function insert(array $userData)
    {
        $query = new SelectQueryBuilder();
        $query->table('users')
            ->select('name', 'email');
        $sql = $query->getQuery();
    }
}