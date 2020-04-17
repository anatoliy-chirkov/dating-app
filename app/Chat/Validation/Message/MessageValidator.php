<?php

namespace Chat\Validation\Message;

class MessageValidator implements IValidator
{
    public static function isValidPayload(object $payload)
    {
        return (!empty($payload->text) || !empty($payload->attachmentId))
            && @strpos($payload->text, '<script') === false
            && is_int($payload->receiverId)
            && ($payload->attachmentId === null || is_int($payload->attachmentId))
        ;
    }
}
