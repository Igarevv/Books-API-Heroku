<?php
error_reporting(0);
use App\App;
use Symfony\Component\Dotenv\Dotenv;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Forwarded-With");
header("Access-Control-Allow-Methods: GET, POST, DELETE");

define('APP_PATH', dirname(__DIR__));
var_dump(require APP_PATH . '/configs/database.php');
require_once APP_PATH . '/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(APP_PATH.'/.env');

$container = new \App\Core\Container\Container();
$router = new \App\Core\Routes\Router($container);
$config = new \App\Config\Config();

(new App($container, $router, $config))->run();

