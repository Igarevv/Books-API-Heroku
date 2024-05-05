<?php
error_reporting(0);
use App\App;
use Symfony\Component\Dotenv\Dotenv;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Forwarded-With");
header("Access-Control-Allow-Methods: GET, POST, DELETE");

if ($_SERVER['REQUEST_URI'] === '/swagger'){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Forwarded-With");
    header("Access-Control-Allow-Methods: GET, POST, DELETE");
    require APP_PATH . '/public/swagger/index.php';
    exit();
}

define('APP_PATH', dirname(__DIR__));

require_once APP_PATH.'/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(APP_PATH.'/.env');

$container = new \App\Core\Container\Container();
$router = new \App\Core\Routes\Router($container);
$config = new \App\Config\Config();

(new App($container, $router, $config))->run();

