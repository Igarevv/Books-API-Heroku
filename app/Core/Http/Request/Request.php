<?php

namespace App\Core\Http\Request;

use App\Core\Http\Response\ResponseInterface;
use App\Core\Validator\Validator;
use App\Core\Validator\ValidatorInterface;

class Request implements RequestInterface, JsonRequestInterface
{

    private array $jsonData = [];

    public function __construct(
      public readonly array $get,
      public readonly array $post,
      public readonly array $cookie,
      public readonly array $files,
      public readonly array $server,
    )
    {
    }

    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function uri(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function method(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function input(string $key = null, $default = null): mixed
    {
        return $this->post[$key] ?? $this->get ?? $default;
    }

    public function setJsonData(array $jsonData): void
    {
        $this->jsonData = $jsonData;
    }

    public function inputJsonData(string $key = ''): string|array
    {
        if (trim($key) === '') {
            return $this->jsonData;
        }
        if (array_key_exists($key, $this->jsonData)) {
            return $this->jsonData[$key];
        }
        return array();
    }
}