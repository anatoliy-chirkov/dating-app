<?php

namespace WebSocket\Validation\Message;

class MessageWasReadValidator implements IValidator
{
    public static function isValidPayload(object $payload)
    {
        return !empty($payload->messageId) && is_int($payload->messageId);
    }
}
