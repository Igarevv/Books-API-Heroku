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

$dotenv = new Dotenv();
$dotenv->load(APP_PATH.'/.env');

$db = parse_url($_ENV['DB_URL']);

return [
  'driver'   => 'pgsql',
  'host'     => $db['host'],
  'user'     => $db['user'],
  'password' => $db['pass'],
  'dbname'   => ltrim($db['path'], '/')
];
