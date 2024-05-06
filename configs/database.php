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

$db = parse_url("postgres://u3ie1bpsuob73q:pb33ac2648631cb865e8f21145b03bf486fa3e7c786ef967cea366d2f1312f66c@c7vbm80blivm58.cluster-czz5s0kz4scl.eu-west-1.rds.amazonaws.com:5432/dccbia0l7bl0i6");

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
