<?php
namespace App\Core\Routes;
use App\Core\Request\RequestInterface;

class Router implements RouteInterface
{
    private array $routes;
    public function __construct(
      private RequestInterface $request
    )
    {
        $this->routes[] = $this->getRoutesFromConfig();
    }

    public function dispatch(string $uri, string $method)
    {
        $dispatchData = [];
        foreach (Route::getRoutes() as $routeConfiguration){
            $routeDispatcher = new RouteDispatcher($routeConfiguration, $uri);
            $dispatchElement = $routeDispatcher->dispatch();

            if($dispatchElement !== false){
                $dispatchData = $dispatchElement;
            }
        }
        dd($dispatchData);
    }
    private function getRoutesFromConfig()
    {
        return require_once APP_PATH . '/app/Config/routes.php';
    }
}