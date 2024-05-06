<?php

namespace App\Core\Database;

use App\Config\ConfigInterface;
use PDO;

class Database implements DatabaseInterface
{

    private ConfigInterface $config;

    private ?\PDO $pdo;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }
    public function execute(string $sql, array $parameters = []): \PDOStatement|false
    {
        $statement = $this->prepare($sql);

        foreach ($parameters as $key => $parameter){
            $statement->bindValue($key, $parameter);
        }

        if($statement->execute()){
            return $statement;
        }
        return false;
    }
    private function connect(): void
    {
        $params = $this->config->get('database');

        $defaultOptions = [
          PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $dsn = "{$params['driver']}:host={$params['host']};dbname={$params['dbname']}";

            $this->pdo = new PDO($dsn, $params['user'], $params['pass'], $defaultOptions);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            exit("Database connection failed(if you run app via docker, please wait a few min): {$e->getMessage()}");
        }
    }
    public function lastInsertedId(): false|string
    {
        return $this->pdo->lastInsertId();
    }
    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }
    public function commitTransaction(): void
    {
        $this->pdo->commit();
    }
    public function rollBack(): void
    {
        $this->pdo->rollBack();
    }
    public function prepare(string $sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }
    private function disconnect(): void
    {
        $this->pdo = null;
    }

}
