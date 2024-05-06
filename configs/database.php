<?php

/*return [
  'driver'   => 'mysql',
  'dbname'   => 'BookAPI',
  'host'     => 'mysql', // mysql for docker, localhost for local
  'user'     => 'dev',
  'password' => 'Devbase75_',
];*/

$db = parse_url(getenv("DATABASE_URL"));

return [
  'driver'   => 'pgsql',
  'host'     => $db['host'],
  'user'     => $db['user'],
  'password' => $db['pass'],
];
