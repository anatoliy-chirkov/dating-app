<?php

namespace WebSocket\Core;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use WebSocket\Validation\Validator;

class Main implements MessageComponentInterface
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // nothing to do
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $validator = new Validator();

        if (!$validator->isValidMessage($msg)) {
            $from->close();
            return;
        }

        $decodedMessage = @json_decode($msg);
        $payload = $decodedMessage->payload;

        switch ($decodedMessage->type) {
            case IMessageType::AUTHORIZE:
                $this->service->authorize($payload, $from);
                break;
            case IMessageType::MESSAGE:
                $this->service->message($payload, $from);
                break;
            case IMessageType::MESSAGE_WAS_READ:
                $this->service->messageWasRead($payload, $from);
                break;
            default:
                break;
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->service->onCloseConnection($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo 'Exception: ' . $e->getMessage();
        $conn->close();
    }
}
