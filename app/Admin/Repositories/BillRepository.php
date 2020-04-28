<?php

namespace Admin\Repositories;

use Shared\Core\Repository;

class BillRepository extends Repository
{
    public function search(string $dateFrom = null, string $dateTo = null, int $userId = null, int $offset = 0, int $limit = 10)
    {
        $sql = <<<SQL
SELECT b.*, u.name as userName, u.age as userAge, g.fullName AS userCity, 
CASE WHEN i.clientPath is NULL THEN '/img/default.jpg' ELSE i.clientPath END AS userImgLink 
FROM bill b 
INNER JOIN user u ON u.id = b.userId 
LEFT JOIN image i ON i.userId = u.id AND i.isMain = 1 
LEFT JOIN googleGeo AS g ON g.id = u.googleGeoId 
SQL;
        $params = [];

        $sql .= ' ' . 'ORDER BY id DESC';
        $sql .= ' ' . "LIMIT {$limit}";

        if ($offset > 0) {
            $sql .= ' ' . "OFFSET {$offset}";
        }

        return $this->connection->all($sql, $params);
    }

    public function count(string $dateFrom = null, string $dateTo = null, int $userId = null)
    {
        $sql = <<<SQL
SELECT count(id) FROM bill 
SQL;
        return $this->connection->value($sql);
    }
}
