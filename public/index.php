<?php

use App\App;
use App\Config\Config;
use App\Core\Container\Container;
use App\Core\Routes\Router;
use Symfony\Component\Dotenv\Dotenv;

error_reporting(0);


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Forwarded-With");
header("Access-Control-Allow-Methods: GET, POST, DELETE");

define('APP_PATH', dirname(__DIR__));

require_once APP_PATH.'/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(APP_PATH.'/.env');

$container = new Container();

$routes = require APP_PATH . '/configs/routes.php';

$router = new Router($container, $routes);
$config = new Config();

(new App($container, $router, $config))->run();

