<?php

ini_set("log_errors", 1);

use docker\app\App;
use docker\vendor\symfony\dotenv\Dotenv;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Forwarded-With");
header("Access-Control-Allow-Methods: GET, POST, DELETE");

define('APP_PATH', dirname(__DIR__));

require_once APP_PATH.'/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(APP_PATH.'/.env');

$container = new docker\app\Core\Container\Container();

$routes = require APP_PATH . '/configs/routes.php';

$router = new docker\app\Core\Routes\Router($container, $routes);
$config = new docker\app\Config\Config();

(new App($container, $router, $config))->run();

