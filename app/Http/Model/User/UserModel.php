<?php

namespace App\Http\Model\User;

use App\Core\Database\Insert\InsertQueryBuilder;
use App\Http\Model\Model;
use Ramsey\Uuid\Uuid;

class UserModel extends Model
{
    protected static string $table = 'User';

    public static function insert(array $userData): bool
    {
        $uuid = Uuid::uuid4()->toString();

        $sql = self::$insert_builder->table(self::getTable())
          ->values(['uuid', 'name', 'email', 'password'])
          ->getQuery();

        $result = self::$db->execute($sql, [
          ':email' => $userData['email'],
          ':name' => $userData['name'],
          ':password' => password_hash($userData['password'], PASSWORD_DEFAULT),
          ':uuid' => $uuid,
        ]);
        if($result !== false){
            return true;
        }
        return false;
    }
    public static function findUserBy(string $key, string $value): array
    {
        $sql = self::$select_builder->table(self::getTable())
          ->select('email', 'password', 'name', 'uuid')
          ->where($key, '=', $key)
          ->getQuery();

        $stmt = self::$db->execute($sql, ['email' => $value]);

        return $stmt->fetch() ?: [];
    }
}