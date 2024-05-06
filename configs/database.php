<?php

//For Heroku

use Symfony\Component\Dotenv\Dotenv;

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
