<?php

namespace Client\Repositories;

use Shared\Core\Repository;

class TokenRepository extends Repository
{
    public function save(string $token, int $userId)
    {
        $sql = "INSERT INTO token (token, userId) VALUES (?, ?)";
        $this->connection->exec($sql, [$token, $userId]);
    }

    public function find(string $token)
    {
        $sql = "SELECT userId FROM token WHERE token = ?";
        return $this->connection->first($sql, [$token]);
    }

    public function removeToken(string $token)
    {
        $sql = "DELETE FROM token WHERE token = ?";
        $this->connection->exec($sql, [$token]);
    }

    public function removeAllUserTokens(int $userId)
    {
        $sql = "DELETE FROM token WHERE userId = ?";
        $this->connection->exec($sql, [$userId]);
    }
}
