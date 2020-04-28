<?php

namespace Client\Repositories;

use Shared\Core\Repository;

class GoogleGeoRepository extends Repository
{
    public function batch(int $page, int $limit, string $type = null)
    {
        $offset = ($page - 1) * $limit;

        $sql = 'SELECT id, name, fullName FROM googleGeo WHERE id IN (SELECT DISTINCT googleGeoId FROM user)';
        $params = [];

        if ($type) {
            $sql .= ' ' . 'AND type = ?';
            $params[] = $type;
        }

        $sql .= ' ' . "LIMIT {$limit} OFFSET {$offset}";

        return $this->connection->all($sql, $params);
    }

    public function search(string $name, string $type = null)
    {
        $sql = <<<SQL
SELECT g.id, g.name, g.fullName, g.type, parent.name AS parentName
FROM googleGeo AS g
LEFT JOIN googleGeo AS parent ON parent.id = g.parentId
WHERE g.fullName like ? AND id IN (SELECT DISTINCT googleGeoId FROM user)
SQL;
        $params = ["%{$name}%"];

        if ($type) {
            $sql .= ' ' . 'AND g.type = ?';
            $params[] = $type;
        }

        return $this->connection->all($sql, $params);
    }

    public function getByIdArray(array $idArr)
    {
        if (empty($idArr)) {
            return $idArr;
        }

        $idArrIN = implode(', ', $idArr);
        $sql = "SELECT id, name, fullName, type FROM googleGeo WHERE id IN ({$idArrIN})";
        return $this->connection->all($sql);
    }

    public function create(
        string $name,
        string $fullName,
        string $type,
        int $parentId = null,
        string $placeId = null,
        float $lat = null,
        float $lng = null
    ) {
        $sql = 'INSERT INTO googleGeo (name, fullName, type, parentId, placeId, lat, lng) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $this->connection->exec($sql, [$name, $fullName, $type, $parentId, $placeId, $lat, $lng]);
    }

    public function isExistByNameType(string $name, string $type)
    {
        $sql = 'SELECT count(id) FROM googleGeo WHERE name = ? AND type = ?';
        return $this->connection->value($sql, [$name, $type]) > 0;
    }

    public function getIdByNameType(string $name, string $type)
    {
        $sql = 'SELECT id FROM googleGeo WHERE name = ? AND type = ? LIMIT 1';
        return $this->connection->value($sql, [$name, $type]);
    }

    public function getIdByPlaceId(string $placeId)
    {
        $sql = 'SELECT id FROM googleGeo WHERE placeId = ? LIMIT 1';
        return $this->connection->value($sql, [$placeId]);
    }

    public function isExistByPlaceId(string $placeId)
    {
        $sql = 'SELECT count(id) FROM googleGeo WHERE placeId = ?';
        return $this->connection->value($sql, [$placeId]) > 0;
    }
}
