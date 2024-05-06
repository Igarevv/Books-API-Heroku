<?php

namespace App\Http\Model\Repository\Token;

use Booksuse docker\app\Core\Database\DatabaseInterface;
use docker\app\Core\Database\DeleteQueryBuilder\DeleteQueryBuilder;
use docker\app\Core\Database\Insert\InsertQueryBuilder;
use docker\app\Core\Database\Select\SelectQueryBuilder;
use docker\app\Core\Database\Update\UpdateQueryBuilder;

class TokenRepository implements TokenRepositoryInterface
{
    private string $table = "\"Refresh_user_token\"";
    public function __construct(
      private readonly DatabaseInterface $database
    )
    {}
    public function saveToken(string $userId, string $refreshToken, int $expiresIn): bool
    {
        $sql = InsertQueryBuilder::table($this->table)
          ->values(['user_id', 'refresh_token', 'expires_in'])
          ->getQuery();

        $result = $this->database->execute($sql,[
          ':user_id' => $userId,
          ':refresh_token' => $refreshToken,
          ':expires_in' => $expiresIn,
        ]);
        return $result !== false;
    }
    public function findToken(string $token): array
    {
        $sql = SelectQueryBuilder::table($this->table)
          ->select('user_id', 'refresh_token', 'expires_in')
          ->where('refresh_token', '=', 'refresh_token')
          ->getQuery();

        $stmt = $this->database->execute($sql, [':refresh_token' => $token]);

        return $stmt->fetch() ?: [];
    }

    public function updateRefreshToken(string $newRefresh, int $user_id): bool
    {
        $sql = UpdateQueryBuilder::table($this->table)
          ->update('refresh_token', $newRefresh)
          ->where('user_id', '=', 'user_id')
          ->getQuery();

        $stmt = $this->database->execute($sql, [':user_id' => $user_id]);

        return $stmt !== false;
    }
    public function deleteRefresh(string $refreshToken): bool
    {
        $sql = DeleteQueryBuilder::table($this->table)
          ->where('refresh_token', '=', 'refresh_token')
          ->getQuery();
        $stmt = $this->database->execute($sql, [':refresh_token' => $refreshToken]);

        return $stmt !== false;
    }
}