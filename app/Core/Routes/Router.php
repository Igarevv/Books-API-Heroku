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

    public function __construct(
      private readonly Container $container
    ) {
        $this->getRoutesFromConfig();
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \JsonException
     */
    public function dispatch(string $uri, string $httpMethod, RequestInterface $request): void
    {
        $route = $this->findRoute($uri, $httpMethod);

        if (! $route) {
            (new JsonResponse(Response::NOT_FOUND))->send();
        }

        [$controllerName, $controllerMethod, $middleware, $requestParams,] = $route;

        if ($middleware) {
            Middleware::handleChain($middleware, $request);
        }

        if ($httpMethod === 'POST' && $request->server['CONTENT_TYPE'] === 'application/json') {
            $request->setJsonData(JsonParser::request());
        }

        if ($this->isValidRoute($controllerName, $controllerMethod)) {
            $controller = $this->container->get($controllerName);

            $result = $controller->$controllerMethod($request, ...$requestParams);

            if($result === null){
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
            $dispatchRoute = $routeDispatcher->dispatch($routeConfiguration, $uri);

            if ($dispatchRoute !== false) {
                return $dispatchRoute;
            }
        }
        return false;
    }

    private function isValidRoute(?string $controllerName, ?string $controllerMethod): bool
    {
        return class_exists($controllerName) && method_exists($controllerName,
            $controllerMethod);
    }

    private function getRoutesFromConfig(): void
    {
        require_once APP_PATH.'/configs/routes.php';
    }

}