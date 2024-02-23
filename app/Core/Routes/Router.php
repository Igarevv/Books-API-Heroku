<?php

namespace App\Core\Routes;

use App\Core\Container\Container;
use App\Core\Http\Json\JsonParser;
use App\Core\Http\Request\RequestInterface;
use App\Core\Http\Response\JsonResponse;
use App\Core\Http\Response\Response;
use App\Core\Http\Response\ResponseInterface;
use App\Core\Middleware\Middleware;

class Router implements RouteInterface
{

    private ResponseInterface $response;

    private RequestInterface $request;

    public function __construct(
      private Container $container
    ) {
        $this->getRoutesFromConfig();
    }

    public function dispatch(string $uri, string $httpMethod, RequestInterface $request): void
    {
        $this->request = $request;

        $route = $this->findRoute($uri, $httpMethod);

        if (! $route) {
            (new JsonResponse(Response::NOT_FOUND))->send();
        }

        [$controllerName, $controllerMethod, $middleware, $requestParams,] = $route;

        if ($middleware) {
            $middleware = Middleware::resolve($middleware);
            (new $middleware($this->request))->handle();
        }

        if ($httpMethod === 'POST' && $this->request->server['CONTENT_TYPE'] == 'application/json') {
            $this->request->setJsonData(JsonParser::request());
        }

        if ($this->isValidRoute($controllerName, $controllerMethod)) {
            $controller = $this->container->get($controllerName);

            $result = call_user_func_array([$controller, $controllerMethod],
              [$this->request, ...$requestParams]);

            if($result == null){
                $this->response = new JsonResponse(Response::OK);
            } else{
                $this->response = $result;
            }
        }
        $this->response->send();
    }

    private function findRoute(string $uri, string $method): array|false
    {
        $routeDispatcher = new RouteDispatcher();
        $routes = Route::getRoutes($method);

        foreach ($routes as $routeConfiguration) {
            $dispatchRoute = $routeDispatcher->dispatch($routeConfiguration,
              $uri);

            if ($dispatchRoute !== false) {
                return $dispatchRoute;
            }
        }
        return false;
    }

    private function isValidRoute(
      ?string $controllerName,
      ?string $controllerMethod
    ): bool {
        if (class_exists($controllerName) && method_exists($controllerName,
            $controllerMethod)) {
            return true;
        }
        return false;
    }

    private function getRoutesFromConfig(): void
    {
        require_once APP_PATH.'/configs/routes.php';
    }

}