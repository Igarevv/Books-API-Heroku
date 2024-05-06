<?php

namespace App\Http\Model\Repository\User;

use docker\app\Core\Database\DatabaseInterface;
use docker\app\Core\Database\DeleteQueryBuilder\DeleteQueryBuilder;
use docker\app\Core\Database\Insert\InsertQueryBuilder;
use Booksuse docker\app\Core\Database\Select\SelectQueryBuilder;
use docker\vendor\ramsey\uuid\src\Uuid;

class UserRepository implements UserRepositoryInterface
{
    private string $table = "\"User\"";
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
        return $result !== false;
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

    public function addFavoriteBook(mixed $user_id, mixed $book_id): bool
    {
        $sql = InsertQueryBuilder::table("\"User_Book\"")
          ->values(['user_id', 'book_id'])
          ->getQuery();

        $stmt = $this->database->execute($sql, [':user_id' => $user_id, 'book_id' => $book_id]);

        return $stmt !== false;
    }
    public function isBookInFavorites(mixed $user_id, int $book_id): bool
    {
        $sql = SelectQueryBuilder::table("\"User_Book\"")
          ->select('*')
          ->where('user_id', '=', 'user_id')
          ->where('book_id', '=', 'book_id')
          ->limit(1)
          ->getQuery();

        $favoriteBook = $this->database->execute($sql, [':user_id' => $user_id, ':book_id' => $book_id]);

        return $favoriteBook->rowCount() > 0;
    }

    public function deleteFromFavorites(mixed $user_id, int $book_id): bool
    {
        $sql = DeleteQueryBuilder::table("\"User_Book\"")
          ->where('user_id', '=', 'user_id')
          ->where('book_id', '=', 'book_id')
          ->getQuery();

        $deleted = $this->database->execute($sql, [':user_id' => $user_id, ':book_id' => $book_id]);

        return $deleted->rowCount() > 0;
    }
}