<?php

namespace Client\Repositories;

use Shared\Core\Repository;

class AttachmentRepository extends Repository
{
    public function create(int $chatId, string $serverPath, string $clientPath, int $width, int $height)
    {
        $sql = "INSERT INTO attachment (chatId, serverPath, clientPath, width, height, createdAt) VALUES (?, ?, ?, ?, ?, NOW())";
        $this->connection->exec($sql, [$chatId, $serverPath, $clientPath, $width, $height]);
    }

    public function getByClientPath(string $clientPath): array
    {
        $sql = "SELECT id, height, width, clientPath FROM attachment WHERE clientPath = ?";
        return $this->connection->first($sql, [$clientPath]);
    }

    public function getById(int $id)
    {
        $sql = "SELECT id, clientPath, width, height FROM attachment WHERE id = ?";
        return $this->connection->first($sql, [$id]);
    }

    public function setMessageId(int $id, int $messageId)
    {
        $sql = 'UPDATE attachment SET messageId = ? WHERE id = ?';
        $this->connection->exec($sql, [$messageId, $id]);
    }
}
