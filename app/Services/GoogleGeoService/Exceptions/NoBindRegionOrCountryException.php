<?php

namespace Services\GoogleGeoService\Exceptions;

use Throwable;

class NoBindRegionOrCountryException extends \Exception
{
    protected $code = 500;
    protected $message = 'No region or country';

    public function __construct()
    {
        parent::__construct($this->message, $this->code);
    }
}
