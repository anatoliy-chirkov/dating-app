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

    public function counterActions(int $counterId)
    {
        $sql = 'SELECT * FROM counterAction WHERE counterId = ?';
        return $this->context->query($sql, [$counterId]);
    }

    public function removeCounterActions(int $counterId)
    {
        $sql = 'DELETE FROM counterAction WHERE counterId = ?';
        $this->context->query($sql, [$counterId]);
    }

    public function addCounterAction(int $counterId, int $actionId, string $type, float $multiplier)
    {
        $sql = 'INSERT INTO counterAction (counterId, actionId, type, multiplier) VALUES (?, ?, ?, ?)';
        $this->context->query($sql, [$counterId, $actionId, $type, $multiplier]);
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
