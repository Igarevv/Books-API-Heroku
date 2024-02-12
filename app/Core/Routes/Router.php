<?php

namespace App\Core\Routes;

use App\Core\Http\Request\JsonRequestInterface;
use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\Response;
use App\Core\Http\Response\ResponseInterface;


class Router implements RouteInterface
{
    public function __construct(
      private readonly RequestInterface $request,
      private readonly ResponseInterface $response
    )
    {
        $this->getRoutesFromConfig();
    }

    public function dispatch(string $uri, string $httpMethod): void
    {
        $route = $this->findRoute($uri, $httpMethod);

        if(! $route){
            $this->response->status(Response::NOT_FOUND)->message('404 | Not found')->send();
        }
        [$controllerName, $controllerMethod, $requestParams] = $route;

        if($httpMethod === 'POST' && $this->request->server['CONTENT_TYPE'] == 'application/json'){
            $data = json_decode(file_get_contents("php://input"), true);
            $this->request->setJsonData($data);
        }

        if($this->isValidRoute($controllerName, $controllerMethod)){

            $controller = new $controllerName();

            call_user_func([$controller, 'setRequest'], $this->request);
            call_user_func([$controller, 'setResponse'], $this->response);

            call_user_func_array([$controller, $controllerMethod], $requestParams);
        }
    }
    private function findRoute(string $uri, string $method): array|false
    {
        $routeDispatcher = new RouteDispatcher();
        $routes = Route::getRoutes($method);

        foreach ($routes as $routeConfiguration) {
            $dispatchRoute = $routeDispatcher->dispatch($routeConfiguration, $uri);

            if ($dispatchRoute !== false) {
                return $dispatchRoute;
            }
        }
        return false;
    }

    private function isValidRoute(string $controllerName, string $controllerMethod): bool
    {
        if(class_exists($controllerName) && method_exists($controllerName, $controllerMethod)){
            return true;
        }
        return false;
    }
    private function getRoutesFromConfig()
    {
        return require_once APP_PATH.'/app/Config/routes.php';
    }

}