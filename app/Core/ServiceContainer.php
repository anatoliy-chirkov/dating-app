<?php

namespace Core;

class ServiceContainer
{
    private static $instance;

    private $services = [];

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): ServiceContainer
    {
        if (empty(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function set(string $serviceName, $service)
    {
        $this->services[$serviceName] = $service;

        return $this;
    }

    public function get(string $serviceName)
    {
        return $this->services[$serviceName];
    }
}
