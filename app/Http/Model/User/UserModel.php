<?php

namespace App\Http\Model\User;

use App\App;
use App\Core\Database\DatabaseInterface;
use App\Core\Database\Insert\InsertQueryBuilder;
use App\Http\Model\Model;
use Ramsey\Uuid\Uuid;

class UserModel extends Model
{
    private string $name = '';
    private string $email;
    private string $password;
    private string $uuid;
    public function __construct(string $name, string $email, string $password)
    {
        parent::__construct();
        $this->name = $name;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->uuid = Uuid::uuid4()->toString();
    }

    public function insert(): bool
    {
        $query = new InsertQueryBuilder();

        $sql = $query->table('User')
          ->values(['uuid', 'name', 'email', 'password'])
          ->getQuery();

        $result = $this->db->execute($sql, [
          ':email' => $this->email,
          ':name' => $this->name,
          ':password' => $this->password,
          ':uuid' => $this->uuid,
        ]);
        if($result !== false){
            return true;
        }
        return false;
    }
}