<?php

namespace App\Core\Routes;

use Booksuse docker\app\Core\Container\Container;
use docker\app\Core\Http\Json\JsonParser;
use docker\app\Core\Http\Request\RequestInterface;
use docker\app\Core\Http\Response\JsonResponse;
use docker\app\Core\Http\Response\Response;
use docker\app\Core\Http\Response\ResponseInterface;
use docker\app\Core\Middleware\Middleware;use Books

class Router implements RouteInterface
{

    private ResponseInterface $response;

    public function __construct(
      private readonly Container $container,
      private readonly array $routes
    ) {
        $this->getRoutesFromConfig();
    }

    /**
     * @throws docker\vendor\psr\container\src\ContainerExceptionInterface
     * @throws docker\vendor\psr\container\src\NotFoundExceptionInterface
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

        foreach ($this->routes as $routeConfiguration) {
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