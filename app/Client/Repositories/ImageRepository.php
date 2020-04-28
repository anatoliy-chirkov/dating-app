<?php

namespace Client\Repositories;

use Shared\Core\Repository;

class ImageRepository extends Repository
{
    public function create(int $userId, string $serverPath, string $clientPath, int $width, int $height, bool $isMain = false)
    {
        $sql = "INSERT INTO image (userId, serverPath, clientPath, width, height, isMain, createdAt) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $this->connection->exec($sql, [$userId, $serverPath, $clientPath, $width, $height, (int) $isMain]);
    }

    public function getByIdAndUserId(int $id, int $userId)
    {
        $sql = 'SELECT * FROM image WHERE id = ? AND userId = ?';
        return $this->connection->first($sql, [$id, $userId]);
    }

    public function getByClientPath(string $clientPath)
    {
        $sql = "SELECT id FROM image WHERE clientPath = ?";
        return $this->connection->first($sql, [$clientPath]);
    }

    public function getUserImages(int $userId)
    {
        $sql = "SELECT * FROM image WHERE userId = ?";
        return $this->connection->all($sql, [$userId]);
    }

    public function markImageAsMain(int $imageId, int $userId)
    {
        $sql = "UPDATE image SET isMain = 0 WHERE userId = ?";
        $this->connection->exec($sql, [$userId]);

        $sql = "UPDATE image SET isMain = 1 WHERE id = ? AND userId = ?";
        $this->connection->exec($sql, [$imageId, $userId]);
    }

    public function deleteOne(int $imageId, int $userId)
    {
        $sql = 'DELETE FROM image WHERE id = ? AND userId = ?';
        $this->connection->exec($sql, [$imageId, $userId]);
    }
}
