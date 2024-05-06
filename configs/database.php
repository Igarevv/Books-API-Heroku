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

$dotEnv = new Dotenv();
$dotEnv->load(APP_PATH.'/.env');

return [
  'driver'   => 'pgsql',
  'host'     => $_ENV['DB_HOST'],
  'user'     => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASS'],
  'dbname'   => $_ENV['DB_NAME']
];
