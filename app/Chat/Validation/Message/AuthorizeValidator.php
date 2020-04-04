<?php

namespace Chat\Validation\Message;

class AuthorizeValidator implements IValidator
{
    private const TOKEN_LENGTH = 32;

    public static function isValidPayload(object $payload)
    {
        return !empty($payload->token) && @strlen($payload->token) === self::TOKEN_LENGTH;
    }
}
