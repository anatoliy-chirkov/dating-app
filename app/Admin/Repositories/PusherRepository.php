<?php

namespace Admin\Repositories;

use Core\Repository;

class PusherRepository extends Repository
{
    public function commands()
    {
        $sql = 'SELECT * FROM pusherCommand';
        return $this->context->query($sql);
    }

    public function pushers()
    {
        $sql = 'SELECT * FROM pusher';
        return $this->context->query($sql);
    }

    public function pusher(int $id)
    {
        $sql = 'SELECT * FROM pusher WHERE id = ?';
        $rows = $this->context->query($sql, [$id]);
        return !empty($rows) ? $rows[0] : null;
    }

    public function addPusher(string $name, int $pusherCommandId, float $price, bool $isActive)
    {
        $sql = 'INSERT INTO pusher (name, pusherCommandId, price, isActive) VALUES (?, ?, ?, ?)';
        $this->context->query($sql, [$name, $pusherCommandId, $price, $isActive]);
    }

    public function updatePusher(int $id, string $name, int $pusherCommandId, float $price, bool $isActive)
    {
        $sql = 'UPDATE pusher SET name = ?, pusherCommandId = ?, price = ?, isActive = ? WHERE id = ?';
        $this->context->query($sql, [$name, $pusherCommandId, $price, $isActive, $id]);
    }

    public function userPushers(int $userId)
    {
        $sql = 'SELECT * FROM userPusher WHERE userId = ?';
        return $this->context->query($sql, [$userId]);
    }

    public function addUserPusher(int $userId, int $pusherId)
    {
        $sql = 'INSERT INTO userPusher (userId, pusherId, createdAt) VALUES (?, ?, NOW())';
        $this->context->query($sql, [$userId, $pusherId]);
    }
}
