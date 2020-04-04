<?php

namespace Chat\Validation\Message;

interface IValidator
{
    public static function isValidPayload(object $payload);
}
