<?php

namespace App\Core\Cookie;

final class Cookie
{
    public static function set(string $name, string $value, int $time = 43200, string $path = ""): void
    {
        setcookie($name, $value, time() + $time, path: $path, httponly: true);
    }
    public static function delete(string $name): void
    {
        setcookie($name, '', time() - 60);
    }
}