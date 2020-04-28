<?php

namespace Admin\Repositories;

use Shared\Core\Repository;

class CounterRepository extends Repository
{
    public function actions()
    {
        $sql = 'SELECT * FROM action';
        return $this->connection->all($sql);
    }

    public function counters()
    {
        $sql = 'SELECT * FROM counter';
        return $this->connection->all($sql);
    }

    public function getActiveCounters()
    {
        $sql = 'SELECT id, name FROM counter WHERE isActive = true';
        return $this->connection->all($sql);
    }

    public function counter(int $id)
    {
        $sql = 'SELECT * FROM counter WHERE id = ?';
        return $this->connection->first($sql, [$id]);
    }

    public function createCounter(string $name, bool $isActive, ?string $about)
    {
        $sql = 'INSERT INTO counter (name, about, isActive) VALUES (?, ?, ?)';
        $this->connection->exec($sql, [$name, $about, $isActive]);
    }

    public function updateCounter(int $id, string $name, bool $isActive, ?string $about)
    {
        $sql = 'UPDATE counter SET name = ?, about = ?, isActive = ? WHERE id = ?';
        $this->connection->exec($sql, [$name, $about, $isActive, $id]);
    }

    public function counterActions(int $counterId)
    {
        $sql = 'SELECT ca.*, a.name as actionName FROM counterAction ca INNER JOIN action a ON a.id = ca.actionId WHERE counterId = ?';
        return $this->connection->all($sql, [$counterId]);
    }

    public function counterAction(int $id)
    {
        $sql = 'SELECT * FROM counterAction WHERE id = ? LIMIT 1';
        return $this->connection->first($sql, [$id]);
    }

    public function getCounterNameByActionName(string $actionName): string
    {
        $sql = <<<SQL
SELECT c.name FROM action a 
INNER JOIN counterAction ca ON ca.actionId = a.id 
INNER JOIN counter c ON c.id = ca.counterId 
WHERE a.name = ? AND c.isActive = true 
SQL;
        return $this->connection->value($sql, [$actionName]);
    }

    public function getCountDataByActionUser(string $actionName, int $userId)
    {
        $sql = <<<SQL
SELECT uc.id as userCounterId, uc.count as userCounterCount, ca.type as counterActionType, ca.multiplier as counterActionMultiplier, ca.counterLimit as counterActionCounterLimit FROM action a 
INNER JOIN counterAction ca ON ca.actionId = a.id 
INNER JOIN userCounter uc ON uc.counterId = ca.counterId 
INNER JOIN counter c ON c.id = uc.counterId 
WHERE a.name = ? AND uc.userId = ? AND c.isActive = true 
SQL;
        return $this->connection->all($sql, [$actionName, $userId]);
    }

    public function removeCounterActions(int $counterId)
    {
        $sql = 'DELETE FROM counterAction WHERE counterId = ?';
        $this->connection->exec($sql, [$counterId]);
    }

    public function addCounterAction(int $counterId, int $actionId, string $type, float $multiplier, ?int $counterLimit, ?int $actionLimit, ?int $productId)
    {
        $sql = 'INSERT INTO counterAction (counterId, actionId, type, multiplier, counterLimit, actionLimit, productId) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $this->connection->exec($sql, [$counterId, $actionId, $type, $multiplier, $counterLimit, $actionLimit, $productId]);
    }

    public function updateCounterAction(int $id, int $actionId, string $type, float $multiplier, ?int $counterLimit, ?int $actionLimit, ?int $productId)
    {
        $sql = 'UPDATE counterAction SET actionId = ?, type = ?, multiplier = ?, counterLimit = ?, actionLimit = ?, productId = ? WHERE id = ?';
        $this->connection->exec($sql, [$actionId, $type, $multiplier, $counterLimit, $actionLimit, $productId, $id]);
    }

    public function getUserCounters(int $userId)
    {
        $sql = <<<SQL
SELECT uc.id, uc.count, c.name, c.about FROM userCounter uc 
INNER JOIN counter c ON c.id = uc.counterId 
WHERE uc.userId = ? AND c.isActive = true 
SQL;
        return $this->connection->all($sql, [$userId]);
    }

    public function addUserCounter(int $userId, int $counterId)
    {
        $sql = 'INSERT INTO userCounter (userId, counterId, count) VALUES (?, ?, 0)';
        $this->connection->exec($sql, [$userId, $counterId]);
    }

    public function increaseUserCounter(int $id, int $count)
    {
        $sql = 'UPDATE userCounter SET count = count + ? WHERE id = ?';
        $this->connection->exec($sql, [$count, $id]);
    }

    public function reduceUserCounter(int $id, int $count)
    {
        $sql = 'UPDATE userCounter SET count = count - ? WHERE id = ?';
        $this->connection->exec($sql, [$count, $id]);
    }
}
