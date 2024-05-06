<?php
use Symfony\Component\Dotenv\Dotenv;

// For local
/*return [
  'driver'   => 'mysql',
  'dbname'   => 'BookAPI',
  'host'     => 'mysql', // mysql for docker, localhost for local
  'user'     => 'dev',
  'password' => 'Devbase75_',
];*/

//For Heroku

$db = parse_url(getenv("DB_URL"));

$pdo = "pgsql:" . sprintf(
    "host=%s;port=%s;user=%s;password=%s;dbname=%s",
    $db["host"],
    $db["port"],
    $db["user"],
    $db["pass"],
    ltrim($db["path"], "/")
  );
echo $pdo;

return [
  'driver'   => 'pgsql',
  'host'     => $_ENV['DB_HOST'],
  'user'     => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASS'],
  'dbname'   => $_ENV['DB_NAME']
];
