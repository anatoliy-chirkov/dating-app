<?php

namespace Client\Repositories;

use Client\Repositories\Helpers\Page;
use Shared\Core\Repository;

class VisitRepository extends Repository
{
    private const RESULTS_PER_PAGE = 14;

    public function saveVisit(int $pageOwnerId, int $visitorId)
    {
        $sql = 'INSERT INTO visit (pageOwnerId, visitorId, time) VALUES (?, ?, NOW())';
        $this->connection->exec($sql, [$pageOwnerId, $visitorId]);
    }

    public function getPageVisits(int $userId, int $page = 1)
    {
        $sql = <<<SQL
SELECT v.id, v.time, g.fullName as city, u.id as userId, u.name, u.age, u.sex, u.lastConnected, u.isConnected,
CASE WHEN i.clientPath is NULL THEN '/img/default.jpg' ELSE i.clientPath END AS clientPath
FROM visit v 
INNER JOIN user u ON  v.visitorId = u.id 
LEFT JOIN image i ON i.userId = u.id AND i.isMain = 1 
LEFT JOIN googleGeo g ON g.id = u.googleGeoId 
WHERE pageOwnerId = ? AND v.time IN (
    SELECT max(visit.time) 
    FROM visit 
    GROUP BY DATE_FORMAT(time, '%Y%m%d%H'), visitorId 
    HAVING visit.visitorId = v.visitorId
) 
ORDER BY v.id DESC 
SQL;

        $sql .= ' ' . (new Page($page, self::RESULTS_PER_PAGE))->getSql();

        return $this->connection->all($sql, [$userId]);
    }

    public function getPageVisitsCount(int $userId)
    {
        $sql = <<<SQL
SELECT count(*) FROM 
(
    SELECT v.id 
    FROM visit v
    WHERE pageOwnerId = ? 
    AND v.time IN (
        SELECT max(visit.time) 
        FROM visit 
        GROUP BY DATE_FORMAT(time, '%Y%m%d%H'), visitorId 
        HAVING visit.visitorId = v.visitorId
    ) 
) AS v1
SQL;
        return $this->connection->value($sql, [$userId]);
    }

    public function getNotSeenVisitsCount(int $userId)
    {
        $sql = <<<SQL
SELECT count(*) FROM 
(
    SELECT v.id 
    FROM visit v
    WHERE pageOwnerId = ? 
    AND hasSeen = false 
    AND v.time IN (
        SELECT max(visit.time) 
        FROM visit 
        GROUP BY DATE_FORMAT(time, '%Y%m%d'), visitorId 
        HAVING visit.visitorId = v.visitorId
    ) 
) AS v1
SQL;
        return $this->connection->value($sql, [$userId]);
    }

    public function setAllVisitsHasSeen(int $userId)
    {
        $sql = 'UPDATE visit SET hasSeen = true WHERE pageOwnerId = ?';
        $this->connection->exec($sql, [$userId]);
    }
}
