<?php

namespace WebSocket\Validation\Message;

interface IValidator
{
    public static function isValidPayload(object $payload);
}
