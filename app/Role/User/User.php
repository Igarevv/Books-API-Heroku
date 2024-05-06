<?php

namespace App\Role\User;

use Booksuse docker\app\Core\Database\DatabaseInterface;
use docker\app\Core\Database\Select\SelectQueryBuilder;
use docker\app\Core\Database\Update\UpdateQueryBuilder;
use docker\vendor\psr\container\src\ContainerInterface;

class User implements UserInterface
{
    protected DatabaseInterface $database;
    public function __construct(protected ContainerInterface $container)
    {
        require_once APP_PATH . '/app/bootstrap.php';
        $this->database = $container->get(DatabaseInterface::class);
    }

    public function findRoleById(int $id): false|array
    {
        $sql = SelectQueryBuilder::table('User')
          ->select('is_admin')
          ->where('id', '=', 'id')
          ->getQuery();

        $statement = $this->database->execute($sql, [':id' => $id]);

        return $statement->fetch() ?? false;
    }

    public function updateUserRole(int $userId, int $role): bool
    {
        $sql = UpdateQueryBuilder::table('User')
          ->update('is_admin', $role)
          ->where('id', '=', 'id')
          ->getQuery();

        $statement = $this->database->execute($sql, [':id' => $userId]);
        return $statement !== false;
    }

}