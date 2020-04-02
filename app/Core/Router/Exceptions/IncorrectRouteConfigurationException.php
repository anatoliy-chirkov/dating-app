<?php

namespace Core\Router\Exceptions;

class IncorrectRouteConfigurationException extends \Exception
{
    protected $message = 'Incorrect route configuration';
    protected $code = 500;

    public function __construct()
    {
        parent::__construct($this->message, $this->code);
    }
}
