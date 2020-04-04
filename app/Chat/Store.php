<?php

namespace Chat;

use Ratchet\ConnectionInterface;

class Store
{
    private $connections = [];
    private $userData    = [];

    public function setUserConnection(int $userId, ConnectionInterface $conn)
    {
        $this->connections[$userId] = $conn;
    }

    public function setUserData(int $userId, array $userData)
    {
        $this->userData[$userId] = $userData;
    }

    public function getUserIdByConnection(ConnectionInterface $conn)
    {
        return array_search($conn, $this->connections);
    }

    public function getConnectionByUserId(int $userId): ?ConnectionInterface
    {
        return isset($this->connections[$userId]) ? $this->connections[$userId] : null;
    }

    public function getUserData(int $userId)
    {
        return isset($this->userData[$userId]) ? $this->userData[$userId] : null;
    }

    public function remove(ConnectionInterface $conn)
    {
        $userId = array_search($conn, $this->connections);
        unset($this->connections[$userId], $this->userData[$userId]);
    }
}
