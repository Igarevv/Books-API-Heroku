<?php

namespace App\Http\Model\Repository\User;

use App\Core\Database\DatabaseInterface;
use App\Core\Database\Insert\InsertQueryBuilder;
use App\Core\Database\Select\SelectQueryBuilder;
use Ramsey\Uuid\Uuid;

class UserRepository implements UserRepositoryInterface
{
    private string $table = 'User';
    public function __construct(
      private readonly DatabaseInterface $database
    )
    {}
    public function insert(array $userData): bool
    {
        $sql = InsertQueryBuilder::table($this->table)
          ->values(['name', 'email', 'password'])
          ->getQuery();

        $result = $this->database->execute($sql, [
          ':email' => $userData['email'],
          ':name' => $userData['name'],
          ':password' => password_hash($userData['password'], PASSWORD_DEFAULT),
        ]);
        if($result !== false){
            return true;
        }
        return false;
    }
    public function findUserBy(string $key, string $value): array
    {
        $sql = SelectQueryBuilder::table($this->table)
          ->select('email', 'password', 'name', 'id', 'is_admin')
          ->where($key, '=', $key)
          ->getQuery();

        $stmt = $this->database->execute($sql, [$key => $value]);

        return $stmt->fetch() ?: [];
    }
}