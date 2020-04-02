<?php

namespace Core;

class Autoloader
{
    private function __construct() {}

    public static function register()
    {
        return spl_autoload_register(static function ($class) {
            $path = APP_PATH . '/' . str_replace('\\', '/', $class) . '.php';

            if (file_exists($path)) {
                require_once $path;
            }
        });
    }
}
