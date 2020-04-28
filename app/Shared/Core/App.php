<?php

namespace Shared\Core;

class App extends Singleton
{
    private $params    = [];
    private $bindings  = [];
    private $instances = [];

    public static function setParam(string $key, $value)
    {
        self::getInstance()->params[$key] = $value;
    }

    public static function getParam(string $key)
    {
        return self::getInstance()->params[$key];
    }

    public static function bind(string $key, string $className)
    {
        self::getInstance()->bindings[$key] = $className;
    }

    public static function get(string $key)
    {
        $app = self::getInstance();

        if (empty($app->instances[$key])) {
            return new $app->bindings[$key];
        }

        return $app->instances[$key];
    }
}
