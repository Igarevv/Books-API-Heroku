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
        $driver = $this->config->get('database.driver');
        $host = $this->config->get('database.host');
        $dbname = $this->config->get('database.dbname');
        $user = $this->config->get('database.user');
        $password = $this->config->get('database.password');

        $defaultOptions = [
          PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $dsn = "$driver:host=$host;dbname=$dbname";

            $this->pdo = new PDO($dsn, $user, $password, $defaultOptions);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            exit("Database connection failed: {$e->getMessage()}");
        }
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