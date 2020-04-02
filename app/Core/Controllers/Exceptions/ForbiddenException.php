<?php

namespace Core\Controllers\Exceptions;

class ForbiddenException extends \Exception
{
    protected $message = 'Forbidden';
    protected $code = 403;

    public function __construct()
    {
        parent::__construct($this->message, $this->code);
    }
}
