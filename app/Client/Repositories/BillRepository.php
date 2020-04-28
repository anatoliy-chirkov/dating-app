<?php

namespace Client\Repositories;

use Shared\Core\Repository;

class BillRepository extends Repository
{
    public function create(float $amount, int $userId): int
    {
        $sql = 'INSERT INTO bill (amount, userId, createdAt) VALUES (?, ?, NOW())';
        $this->connection->exec($sql, [$amount, $userId]);

        $sql = 'SELECT id FROM bill WHERE amount = ? AND userId = ? ORDER BY id DESC LIMIT 1';
        return $this->connection->value($sql, [$amount, $userId]);
    }

    public function getOne(int $id)
    {
        $sql = 'SELECT * FROM bill WHERE id = ? LIMIT 1';
        return $this->connection->first($sql, [$id])[0];
    }

    public function setPaid(int $billId, string $paidAt)
    {
        $sql = 'UPDATE bill SET paidAt = ? WHERE id = ?';
        $this->connection->exec($sql, [$paidAt, $billId]);
    }

    public function getUserId(int $billId): int
    {
        $sql = 'SELECT userId FROM bill WHERE id = ? LIMIT 1';
        return $this->connection->value($sql, [$billId]);
    }
}
