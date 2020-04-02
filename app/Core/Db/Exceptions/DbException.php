<?php

namespace Core\Db\Exceptions;

use Throwable;

class DbException extends \Exception
{
    protected $code = 500;
    protected $message;

    public function __construct(string $message)
    {
        parent::__construct($message, $this->code);
    }
}
