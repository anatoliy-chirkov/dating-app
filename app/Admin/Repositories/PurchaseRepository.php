<?php

namespace Admin\Repositories;

use Core\Repository;

class PurchaseRepository extends Repository
{
    public function search(string $dateFrom = null, string $dateTo = null, int $userId = null, int $offset = 0, int $limit = 10)
    {
        $sql = <<<SQL
SELECT p.*, pg.name as productGroupName, pr.name as productName, pr.price as productPrice, u.name as userName, u.age as userAge, g.fullName AS userCity, 
CASE WHEN i.clientPath is NULL THEN '/img/default.jpg' ELSE i.clientPath END AS userImgLink  
FROM purchase p 
INNER JOIN product pr ON pr.id = p.productId 
INNER JOIN productGroup pg ON pg.id = pr.groupId 
INNER JOIN user u ON u.id = p.userId 
LEFT JOIN image i ON i.userId = u.id AND i.isMain = 1 
LEFT JOIN googleGeo AS g ON g.id = u.googleGeoId 
SQL;
        $params = [];

        $sql .= ' ' . 'ORDER BY id DESC';
        $sql .= ' ' . "LIMIT {$limit}";

        if ($offset > 0) {
            $sql .= ' ' . "OFFSET {$offset}";
        }

        return $this->context->query($sql, $params);
    }

    public function count(string $dateFrom = null, string $dateTo = null, int $userId = null)
    {
        $sql = <<<SQL
SELECT count(id) FROM purchase 
SQL;

        return $this->context->query($sql)[0][0];
    }
}
