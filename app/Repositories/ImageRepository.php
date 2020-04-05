<?php

namespace Repositories;

use Core\Db\DbContext;
use Core\ServiceContainer;

class ImageRepository
{
    /** @var DbContext */
    private $dbContext;

    public function __construct()
    {
        $this->dbContext = ServiceContainer::getInstance()->get('db_context');
    }

    public function create(int $userId, string $serverPath, string $clientPath, bool $isMain = false)
    {
        $sql = "INSERT INTO image (userId, serverPath, clientPath, isMain, createdAt) VALUES (?, ?, ?, ?, NOW())";
        $this->dbContext->query($sql, [$userId, $serverPath, $clientPath, (int) $isMain]);
    }

    public function getByClientPath(string $clientPath)
    {
        $sql = "SELECT id FROM image WHERE clientPath = ?";
        $rows = $this->dbContext->query($sql, [$clientPath]);
        return empty($rows) ? null : $rows[0];
    }

    public function getUserImages(int $userId)
    {
        $sql = "SELECT * FROM image WHERE userId = ?";
        return $this->dbContext->query($sql, [$userId]);
    }

    public function markImageAsMain(int $imageId, int $userId)
    {
        $sql = "UPDATE image SET isMain = 1 WHERE id = ? AND userId = ?";
        $this->dbContext->query($sql, [$imageId, $userId]);
    }
}
