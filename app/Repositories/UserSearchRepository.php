<?php

namespace Repositories;

use Core\Repository;

class UserSearchRepository extends Repository
{
    public function logSearch(int $userId, string $searchData)
    {
        $sql = 'INSERT INTO userSearch (userId, data, createdAt) VALUES (?, ?, NOW())';
        $this->context->query($sql, [$userId, $searchData]);
    }

    public function getLastSearch(int $userId)
    {
        $sql = 'SELECT data FROM userSearch WHERE userId = ? ORDER BY createdAt DESC LIMIT 1';
        return $this->context->query($sql, [$userId])[0];
    }
}
