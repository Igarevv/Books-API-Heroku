<?php

namespace App;

use App\Config\Config;
use App\Core\Container\Container;
use App\Core\Database\Database;
use App\Core\Database\DatabaseInterface;
use App\Core\Http\Request\RequestInterface;
use App\Core\Routes\Router;

class App
{

    private RequestInterface $request;

    private static DatabaseInterface $db;

    public function __construct(
      protected Container $container,
      protected Router $router,
      protected Config $config
    ) {
        static::$db = new Database($this->config);
        $this->getBindings();
    }

    public static function db(): DatabaseInterface
    {
        return static::$db;
    }

    public function run()
    {
        $this->request = $this->container->get(RequestInterface::class);
        $this->router->dispatch(
          $this->request->uri(),
          $this->request->method(),
          $this->request
        );
    }

    private function getBindings(): void
    {
        require_once APP_PATH.'/app/bootstrap.php';
    }

}