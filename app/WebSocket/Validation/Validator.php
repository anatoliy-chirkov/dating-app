<?php

namespace WebSocket\Validation;

use WebSocket\Core\IMessageType;
use WebSocket\Validation\Message\AuthorizeValidator;
use WebSocket\Validation\Message\MessageValidator;
use WebSocket\Validation\Message\MessageWasReadValidator;

class Validator
{
    public function isValidMessage(string $message)
    {
        if (!$this->isValidJson($message)) {
            return false;
        }

        $decodedMessage = @json_decode($message);

        if (!$this->isValidMessageType($decodedMessage) && !$this->isValidMessagePayload($decodedMessage)) {
            return false;
        }

        switch ($decodedMessage->type) {
            case IMessageType::AUTHORIZE:
                return AuthorizeValidator::isValidPayload($decodedMessage->payload);
            case IMessageType::MESSAGE:
                return MessageValidator::isValidPayload($decodedMessage->payload);
            case IMessageType::MESSAGE_WAS_READ:
                return MessageWasReadValidator::isValidPayload($decodedMessage->payload);
            default:
                return false;
        }
    }

    private function isValidJson(string $json)
    {
        @json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }

    private function isValidMessageType(object $decodedMessage)
    {
        return !empty($decodedMessage->type);
    }

    private function isValidMessagePayload(object $decodedMessage)
    {
        return !empty($decodedMessage->payload) && is_object($decodedMessage->payload);
    }
}
