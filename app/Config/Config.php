<?php

namespace App\Config;

use Booksclass Config implements ConfigInterface
{
    public function get(string $key, $default = null): mixed
    {
        $fileInfo = explode('.', $key);

        $file = $fileInfo[0];
        $key = $fileInfo[1] ?? '';

        $configPath = APP_PATH . "/configs/$file.php";

        if(! file_exists($configPath)){
            return $default;
        }

        $config = require $configPath;
        return $config[$key] ?? $config;
    }
}