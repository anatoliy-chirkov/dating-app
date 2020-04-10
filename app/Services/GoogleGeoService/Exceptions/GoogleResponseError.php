<?php

namespace Services\GoogleGeoService\Exceptions;

use Throwable;

class GoogleResponseError extends \Exception
{
    protected $code = 500;
    protected $message = 'Error while requesting to Google service';

    public function __construct()
    {
        parent::__construct($this->message, $this->code);
    }
}
