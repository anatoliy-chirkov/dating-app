<?php

namespace Shared\Core;

abstract class Singleton
{
    protected static function getInstance(): self
    {
        static $_instances;
        $calledClass = static::class;
        if (empty($_instances[$calledClass])) {
            $_instances[$calledClass] = new $calledClass();
        }
        return $_instances[$calledClass];
    }

    private function __construct() {}
    private function __clone() {}
}
