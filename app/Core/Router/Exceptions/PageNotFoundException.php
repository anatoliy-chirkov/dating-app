<?php

namespace Core\Router\Exceptions;

class PageNotFoundException extends \Exception
{
    protected $message = 'Page not found';
    protected $code = 404;

    public function __construct()
    {
        parent::__construct($this->message, $this->code);
    }
}
