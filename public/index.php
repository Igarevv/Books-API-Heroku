<?php
define('APP_PATH', dirname(__DIR__));
require_once APP_PATH . '/vendor/autoload.php';

use App\App;


$container = new \App\Core\Container\Container();
$router = new \App\Core\Routes\Router($container);
$config = new \App\Config\Config();

(new App($container, $router, $config))->run();
