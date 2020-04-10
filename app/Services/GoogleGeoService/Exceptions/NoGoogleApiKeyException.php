<?php

namespace Services\GoogleGeoService\Exceptions;

class NoGoogleApiKeyException extends \Exception
{
    protected $code = 500;
    protected $message = 'No Google API key';

    public function __construct()
    {
        parent::__construct($this->message, $this->code);
    }
}
