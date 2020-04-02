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

    public function create(int $userId, string $path, bool $isMain = false)
    {
        $sql = "INSERT INTO image (userId, path, isMain, createdAt) VALUES (?, ?, ?, NOW())";
        $this->dbContext->query($sql, [$userId, $path, (int) $isMain]);
    }

    public function getByPath(string $path)
    {
        $sql = "SELECT id FROM image WHERE path = ?";
        $rows = $this->dbContext->query($sql, [$path]);
        return empty($rows) ? null : $rows[0];
    }
}
