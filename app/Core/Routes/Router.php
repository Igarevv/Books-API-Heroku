<?php

namespace App\Core\Routes;

use App\Core\Request\RequestInterface;

class Router implements RouteInterface
{

    private array $routes;

    public function __construct(
      private readonly RequestInterface $request
    ) {
        $this->routes[] = $this->getRoutesFromConfig();
    }

    /**
     * Перебирает роуты из файла Config->routes, делигирует задачу другому классу,
     * который делает следующие вещи:
     * - находит местоположение параметров шаблона('/users/{id}') URI из конфига и записывает в массив, например array(1) => 'id'
     *   если бы было /{id} то было бы array(0) => 'id'
     * - далее исходя из того есть ли в массиве из URI строк запроса элемент такой же по ключу как в массиве местополежний
     *   то наша строка запроса преобразуется из вида: /users/10, в шаблон URI запроса /users/{.*}
     * - и финальный этап мы сравниваем наш превращенный в шаблон URI запроса, с тем шаблоном который задан в конфиге: /users/{.*} = /users/{id} и возвращаем ответ
     */
    public function dispatch(string $uri, string $method)
    {
        $dispatchData = [];
        $routeDispatcher = new RouteDispatcher();

        foreach (Route::getRoutes() as $routeConfiguration) {
            $dispatchElement = $routeDispatcher->dispatch($routeConfiguration, $uri);

            if ($dispatchElement !== false) {
                $dispatchData = $dispatchElement;
            }
        }
    }

    private function getRoutesFromConfig()
    {
        return require_once APP_PATH.'/app/Config/routes.php';
    }

}