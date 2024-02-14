<?php

namespace App\Core\Container;

class Container
{

    private array $entries = [];

    public function get(string $key): mixed
    {
        if ($this->has($key)) {
            $entry = $this->entries[$key];
            if (is_callable($entry)) {
                return $entry($this);
            }
            $key = $entry;
        }
        return $this->resolve($key);
    }

    public function bind(string $key, callable|string $concrete): void
    {
        $this->entries[$key] = $concrete;
    }

    public function has(string $key): bool
    {
        return isset($this->entries[$key]);
    }

    public function resolve(string $className)
    {
        try {
            $reflection = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
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
                throw new \Exception("Failed to resolve {$className} because 
                parameter {$name} is missing a type hint.");
            }

            if ($type instanceof \ReflectionNamedType) {
                return $this->get($type->getName());
            }

            throw new \Exception(
              "Failed to resolve class {$className} because invalid param {$name}"
            );
        }, $parameters);

        return $reflection->newInstanceArgs($dependencies);
    }

    public function make(string $key)
    {
        if (isset($this->entries[$key])) {
            $resolver = $this->entries[$key];

            if (is_string($resolver) && class_exists($resolver)) {
                return $this->resolve($resolver);
            } elseif (is_callable($resolver)) {
                return $resolver();
            }
            return $resolver;
        }

        throw new \Exception("Dependency $key not found in container.");
    }

}