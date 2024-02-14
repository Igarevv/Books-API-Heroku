<?php

namespace App\Core\Database;

use App\Config\ConfigInterface;

class Database implements DatabaseInterface
{
    private ConfigInterface $config;
    private \PDO $pdo;
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->connect();
    }

    public function connect(): void
    {
        $driver = $this->config->get('database.driver');
        $host = $this->config->get('database.host');
        $dbname = $this->config->get('database.dbname');
        $user = $this->config->get('database.user');
        $password = $this->config->get('database.password');

        try {
            $dsn = "$driver:host=$host;dbname=$dbname";

            $this->pdo = new \PDO($dsn, $user, $password);

            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e){
            exit("Database connection failed: {$e->getMessage()}");
        }
    }
}