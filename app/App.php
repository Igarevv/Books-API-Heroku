<?php

namespace App;

use App\Config\Config;
use App\Core\Container\Container;
use App\Core\Database\DatabaseInterface;
use App\Core\Http\Request\RequestInterface;
use App\Core\Routes\Router;

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
        var_dump($request->uri());
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