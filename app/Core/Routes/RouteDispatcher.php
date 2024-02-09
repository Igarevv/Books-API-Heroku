<?php

namespace App\Core\Routes;

class RouteDispatcher
{
    private string $requestUri;
    private RouteConfiguration $routeConfiguration;
    private array $paramMap = [];
    public function __construct(RouteConfiguration $routeConfiguration, string $requestUri = '/')
    {
        $this->routeConfiguration = $routeConfiguration;
        $this->requestUri = $requestUri;
    }

    public function dispatch(): array|false
    {
        $this->saveRequestUri();

        $this->setParamMap();

        $this->makeRegexRequest();

        if(preg_match("/^$this->requestUri$/", $this->routeConfiguration->route)){
            return [
              $this->routeConfiguration->getControllerName(),
              $this->routeConfiguration->getIndex()
            ];
        }
        return false;
    }

    public function saveRequestUri(): void
    {
        if($this->requestUri !== '/'){
            $this->requestUri = $this->clean($this->requestUri);
            $this->routeConfiguration->route = $this->clean($this->routeConfiguration->route);
        }
    }
    private function setParamMap(): void
    {
        $routeArray = explode('/', $this->routeConfiguration->route);

        foreach ($routeArray as $key => $param){
            if(preg_match('/{.*}/', $param)){
                $this->paramMap[$key] = preg_replace('/(^{)|(}$)/', '', $param);
            }
        }

    }
    private function makeRegexRequest(): void
    {
        if(empty($this->paramMap))
        {
            return;
        }

        $requestUriArray = explode('/', $this->requestUri);

        foreach ($this->paramMap as $key => $param) {
            if(!isset($requestUriArray[$key])){
                return;
            }
            $requestUriArray[$key] = '{.*}';
        }

        $this->requestUri = implode('/', $requestUriArray);
        $this->prepareRegex();
    }
    private function prepareRegex(): void
    {
        $this->requestUri = str_replace('/', '\/', $this->requestUri);
    }
    private function clean(string $uri): string
    {
        return preg_replace('/(^\/)|(\/$)/', '', $uri);
    }

}