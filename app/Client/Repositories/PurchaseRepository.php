<?php

namespace Client\Repositories;

use Shared\Core\Repository;

class PurchaseRepository extends Repository
{
    public function create(int $userId, int $productId, float $price)
    {
        $sql = 'INSERT INTO purchase (userId, productId, price, createdAt) VALUES (?, ?, ?, NOW())';
        $this->connection->exec($sql, [$userId, $productId, $price]);
    }
}
