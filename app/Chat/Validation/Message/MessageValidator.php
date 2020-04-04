<?php

namespace Chat\Validation\Message;

class MessageValidator implements IValidator
{
    public static function isValidPayload(object $payload)
    {
        return !empty($payload->text)
            && @strpos($payload->text, '<script') === false
            && !empty($payload->receiverId)
            && is_int($payload->receiverId)
        ;
    }
}
