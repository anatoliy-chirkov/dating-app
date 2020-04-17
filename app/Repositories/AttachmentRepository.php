<?php

namespace Repositories;

use Core\Db\DbContext;
use Core\ServiceContainer;

class AttachmentRepository
{
    /** @var DbContext */
    private $dbContext;

    public function __construct()
    {
        $this->dbContext = ServiceContainer::getInstance()->get('db_context');
    }

    public function create(int $chatId, string $serverPath, string $clientPath, int $width, int $height)
    {
        $sql = "INSERT INTO attachment (chatId, serverPath, clientPath, width, height, createdAt) VALUES (?, ?, ?, ?, ?, NOW())";
        $this->dbContext->query($sql, [$chatId, $serverPath, $clientPath, $width, $height]);
    }

    public function getByClientPath(string $clientPath): array
    {
        $sql = "SELECT id, height, width, clientPath FROM attachment WHERE clientPath = ?";
        $rows = $this->dbContext->query($sql, [$clientPath]);
        return empty($rows) ? [] : $rows[0];
    }

    public function getById(int $id)
    {
        $sql = "SELECT id, clientPath, width, height FROM attachment WHERE id = ?";
        $rows = $this->dbContext->query($sql, [$id]);
        return $rows[0];
    }

    public function setMessageId(int $id, int $messageId)
    {
        $sql = 'UPDATE attachment SET messageId = ? WHERE id = ?';
        $this->dbContext->query($sql, [$messageId, $id]);
    }
}
