<?php

namespace Admin\Repositories;

use Core\Repository;

class AdvantageRepository extends Repository
{
    public function permissions()
    {
        $sql = 'SELECT * FROM permission';
        return $this->context->query($sql);
    }

    public function accesses()
    {
        $sql = 'SELECT * FROM access';
        return $this->context->query($sql);
    }

    public function advantageGroups()
    {
        $sql = 'SELECT * FROM advantageGroup';
        return $this->context->query($sql);
    }

    public function addAdvantageGroup(string $name): int
    {
        $sql = 'INSERT INTO advantageGroup (name) VALUES (?)';
        $this->context->query($sql, [$name]);

        $sql = 'SELECT id FROM advantageGroup ORDER BY id DESC LIMIT 1';
        return $this->context->query($sql)[0]['id'];
    }

    public function advantagePermissions(int $advantageId)
    {
        $sql = 'SELECT * FROM advantagePermission WHERE advantageId = ?';
        return $this->context->query($sql, [$advantageId]);
    }

    public function removeAdvantagePermissions(int $advantageId)
    {
        $sql = 'DELETE FROM advantagePermission WHERE advantageId = ?';
        $this->context->query($sql, [$advantageId]);
    }

    public function addAdvantagePermission(int $advantageId, int $permissionId)
    {
        $sql = 'INSERT INTO advantagePermission (advantageId, permissionId) VALUES (?, ?)';
        $this->context->query($sql, [$advantageId, $permissionId]);
    }

    public function getAdvantagesForBuy()
    {
        $forBuyAccessId = 1;
        return $this->getAdvantagesByAccessId($forBuyAccessId);
    }

    private function getAdvantagesByAccessId(int $accessId)
    {
        $sql = <<<SQL
SELECT a.id, a.name, a.price, a.duration, ag.name as groupName 
FROM advantage a 
INNER JOIN advantageGroup ag ON ag.id = a.groupId 
WHERE accessId = ?
SQL;
        return $this->context->query($sql, [$accessId]);
    }

    public function userAdvantages(int $userId)
    {
        $sql = 'SELECT * FROM userAdvantage WHERE userId = ?';
        return $this->context->query($sql, [$userId]);
    }

    public function addUserAdvantage(int $userId, int $advantageId, string $createdAt, string $expiredAt)
    {
        $sql = 'INSERT INTO userAdvantage (userId, advantageId, createdAt, expiredAt) VALUES (?, ?, ?, ?)';
        $this->context->query($sql, [$userId, $advantageId, $createdAt, $expiredAt]);
    }

    public function advantages()
    {
        $sql = 'SELECT * FROM advantage';
        return $this->context->query($sql);
    }

    public function advantage(int $id)
    {
        $sql = 'SELECT * FROM advantage WHERE id = ?';
        $rows = $this->context->query($sql, [$id]);
        return !empty($rows) ? $rows[0] : null;
    }

    public function addAdvantage(string $name, int $groupId, int $accessId, int $duration, float $price, bool $isActive): int
    {
        $sql = 'INSERT INTO advantage (name, groupId, accessId, duration, price, isActive) VALUES (?, ?, ?, ?, ?, ?)';
        $this->context->query($sql, [$name, $groupId, $accessId, $duration, $price, $isActive]);

        $sql = 'SELECT id FROM advantage ORDER BY id DESC LIMIT 1';
        return $this->context->query($sql)[0]['id'];
    }

    public function updateAdvantage(int $id, string $name, int $groupId, int $accessId, int $duration, float $price, bool $isActive)
    {
        $sql = <<<SQL
UPDATE advantage 
SET name = ?, groupId = ?, accessId = ?, duration = ?, price = ?, isActive = ? 
WHERE id = ? 
SQL;
        $this->context->query($sql, [$name, $groupId, $accessId, $duration, $price, $isActive, $id]);
    }
}
