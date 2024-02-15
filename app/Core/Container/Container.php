<?php

namespace App\Core\Container;

use App\Http\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{

    private array $entries = [];

    public function get(string $id): mixed
    {
        if ($this->has($id)) {
            $entry = $this->entries[$id];
            if (is_callable($entry)) {
                return $entry($this);
            }
            $id = $entry;
        }
        return $this->resolve($id);
    }

    public function bind(string $id, callable|string $concrete): void
    {
        $this->entries[$id] = $concrete;
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function resolve(string $className)
    {
        try {
            $reflection = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            throw new \ReflectionException($e->getMessage(), $e->getCode(), $e);
        }

        if ( ! $reflection->isInstantiable()) {
            throw new \Exception("Class {$className} is not instantiable");
        }

        $constructor = $reflection->getConstructor();

        if ( ! $constructor) {
            return new $className();
        }

        $parameters = $constructor->getParameters();

        if ( ! $parameters) {
            return new $className();
        }

        $dependencies = array_map(function (\ReflectionParameter $param) use (
          $className
        ) {
            $name = $param->getName();
            $type = $param->getType();

            if ( ! $type) {
                throw new ContainerException("Failed to resolve {$className} because 
                parameter {$name} is missing a type hint.");
            }

            if ($type instanceof \ReflectionNamedType) {
                return $this->get($type->getName());
            }

            throw new ContainerException(
              "Failed to resolve class {$className} because invalid param {$name}"
            );
        }, $parameters);

        return $reflection->newInstanceArgs($dependencies);
    }
}