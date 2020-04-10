<?php

namespace Repositories;

use Core\Db\DbContext;
use Core\ServiceContainer;

class GoogleGeoRepository
{
    /** @var DbContext */
    private $dbContext;

    public function __construct()
    {
        $this->dbContext = ServiceContainer::getInstance()->get('db_context');
    }

    public function batch(int $page, int $limit)
    {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT id, name, fullName FROM googleGeo LIMIT {$limit} OFFSET {$offset}";
        return $this->dbContext->query($sql);
    }

    public function search(string $cityName)
    {
        $sql = <<<SQL
SELECT g.id, g.name, g.fullName, g.type, parent.name AS parentName
FROM googleGeo AS g
LEFT JOIN googleGeo AS parent ON parent.id = g.parentId
WHERE g.name like ?
SQL;
        return $this->dbContext->query($sql, ["%{$cityName}%"]);
    }

    public function getByIdArray(array $idArr)
    {
        if (empty($idArr)) {
            return $idArr;
        }

        $idArrIN = implode(', ', $idArr);
        $sql = "SELECT id, name, fullName, type FROM googleGeo WHERE id IN ({$idArrIN})";
        return $this->dbContext->query($sql);
    }

    public function create(
        string $name,
        string $type,
        int $parentId = null,
        string $placeId = null,
        float $lat = null,
        float $lng = null,
        string $fullName = null
    ) {
        $sql = 'INSERT INTO googleGeo (name, type, parentId, placeId, lat, lng, fullName) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $this->dbContext->query($sql, [$name, $type, $parentId, $placeId, $lat, $lng, $fullName]);
    }

    public function isExistByNameType(string $name, string $type)
    {
        $sql = 'SELECT count(id) FROM googleGeo WHERE name = ? AND type = ?';
        return $this->dbContext->query($sql, [$name, $type])[0][0] > 0;
    }

    public function getIdByNameType(string $name, string $type)
    {
        $sql = 'SELECT id FROM googleGeo WHERE name = ? AND type = ? LIMIT 1';
        $rows = $this->dbContext->query($sql, [$name, $type]);
        return empty($rows) ? null : $rows[0]['id'];
    }

    public function getIdByPlaceId(string $placeId)
    {
        $sql = 'SELECT id FROM googleGeo WHERE placeId = ? LIMIT 1';
        $rows = $this->dbContext->query($sql, [$placeId]);
        return empty($rows) ? null : $rows[0]['id'];
    }

    public function isExistByPlaceId(string $placeId)
    {
        $sql = 'SELECT count(id) FROM googleGeo WHERE placeId = ?';
        return $this->dbContext->query($sql, [$placeId])[0][0] > 0;
    }
}
