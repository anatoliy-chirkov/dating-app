<?php

namespace Admin\Repositories;

use Core\Repository;

class CounterRepository extends Repository
{
    public function actions()
    {
        $sql = 'SELECT * FROM action';
        return $this->context->query($sql);
    }

    public function counters()
    {
        $sql = 'SELECT * FROM counter';
        return $this->context->query($sql);
    }

    public function counter(int $id)
    {
        $sql = 'SELECT * FROM counter WHERE id = ?';
        $rows = $this->context->query($sql, [$id]);
        return !empty($rows) ? $rows[0] : null;
    }

    public function createCounter(string $name, bool $isActive)
    {
        $sql = 'INSERT INTO counter (name, isActive) VALUES (?, ?)';
        $this->context->query($sql, [$name, $isActive]);
    }

    public function updateCounter(int $id, string $name, bool $isActive)
    {
        $sql = 'UPDATE counter SET name = ?, isActive = ? WHERE id = ?';
        $this->context->query($sql, [$name, $isActive, $id]);
    }

    public function counterActions(int $counterId)
    {
        $sql = 'SELECT ca.*, a.name as actionName FROM counterAction ca INNER JOIN action a ON a.id = ca.actionId WHERE counterId = ?';
        return $this->context->query($sql, [$counterId]);
    }

    public function counterAction(int $id)
    {
        $sql = 'SELECT * FROM counterAction WHERE id = ? LIMIT 1';
        return $this->context->query($sql, [$id]);
    }

    public function removeCounterActions(int $counterId)
    {
        $sql = 'DELETE FROM counterAction WHERE counterId = ?';
        $this->context->query($sql, [$counterId]);
    }

    public function addCounterAction(int $counterId, int $actionId, string $type, float $multiplier, ?int $counterLimit, ?int $actionLimit, ?int $productId)
    {
        $sql = 'INSERT INTO counterAction (counterId, actionId, type, multiplier, counterLimit, actionLimit, productId) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $this->context->query($sql, [$counterId, $actionId, $type, $multiplier, $counterLimit, $actionLimit, $productId]);
    }

    public function updateCounterAction(int $id, int $actionId, string $type, float $multiplier, ?int $counterLimit, ?int $actionLimit, ?int $productId)
    {
        $sql = 'UPDATE counterAction SET actionId = ?, type = ?, multiplier = ?, counteLimit = ?, actionLimit = ?, productId = ? WHERE id = ?';
        $this->context->query($sql, [$actionId, $type, $multiplier, $counterLimit, $actionLimit, $productId, $id]);
    }

    public function userCounter(int $userId)
    {
        $sql = 'SELECT * FROM userCounter WHERE userId = ?';
        return $this->context->query($sql, [$userId]);
    }

    public function addUserCounter(int $userId, int $counterId, int $count)
    {
        $sql = 'INSERT INTO userCounter (userId, counterId, count) VALUES (?, ?, ?)';
        $this->context->query($sql, [$userId, $counterId, $count]);
    }
}
