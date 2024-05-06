<?php

namespace App;

use docker\app\Config\Config;
use docker\app\Core\Container\Container;
use docker\app\Core\Database\DatabaseInterface;
use docker\app\Core\Http\Request\RequestInterface;
use docker\app\Core\Routes\Router;

class App
{

    private static DatabaseInterface $db;
    public function __construct(
      protected Container $container,
      protected Router $router,
      protected Config $config
    ) {
        $this->getBindings();
        static::$db = $this->container->get(DatabaseInterface::class);
    }

    public static function db(): DatabaseInterface
    {
        return static::$db;
    }
    public function run(): void
    {
        $request = $this->container->get(RequestInterface::class);
        $this->router->dispatch(
          $request->uri(),
          $request->method(),
          $request
        );
    }

    private function getBindings(): void
    {
        require_once APP_PATH.'/app/bootstrap.php';
    }

}