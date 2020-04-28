<?php

namespace Client\Repositories;

use Shared\Core\Repository;

class UserSearchRepository extends Repository
{
    public function logSearch(int $userId, string $searchData)
    {
        $sql = 'INSERT INTO userSearch (userId, data, createdAt) VALUES (?, ?, NOW())';
        $this->connection->exec($sql, [$userId, $searchData]);
    }

    public function getLastSearch(int $userId)
    {
        $sql = 'SELECT data FROM userSearch WHERE userId = ? ORDER BY createdAt DESC LIMIT 1';
        return $this->connection->first($sql, [$userId]);
    }
}
