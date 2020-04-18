<?php

namespace Repositories;

use Core\Db\DbContext;
use Core\ServiceContainer;
use Repositories\Helpers\Page;

class VisitRepository
{
    private const RESULTS_PER_PAGE = 14;

    /** @var DbContext  */
    private $dbContext;

    public function __construct()
    {
        $this->dbContext = ServiceContainer::getInstance()->get('db_context');
    }

    public function saveVisit(int $pageOwnerId, int $visitorId)
    {
        $sql = 'INSERT INTO visit (pageOwnerId, visitorId, time) VALUES (?, ?, NOW())';
        $this->dbContext->query($sql, [$pageOwnerId, $visitorId]);
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

        return $this->dbContext->query($sql, [$userId]);
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
        return $this->dbContext->query($sql, [$userId])[0][0];
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
        return $this->dbContext->query($sql, [$userId])[0][0];
    }

    public function setAllVisitsHasSeen(int $userId)
    {
        $sql = 'UPDATE visit SET hasSeen = true WHERE pageOwnerId = ?';
        $this->dbContext->query($sql, [$userId]);
    }
}
