<?php

namespace Repositories;

use Core\Repository;

class PurchaseRepository extends Repository
{
    public function create(int $userId, int $productId, float $price)
    {
        $sql = 'INSERT INTO purchase (userId, productId, price, createdAt) VALUES (?, ?, ?, NOW())';
        $this->context->query($sql, [$userId, $productId, $price]);
    }
}
