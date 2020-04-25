<?php

namespace Repositories;

use Core\Repository;

class ProductRepository extends Repository
{
    public function addProductToUser(int $productId, int $userId, string $createdAt, string $expiredAt)
    {
        $sql = 'INSERT INTO userProduct (userId, productId, createdAt, expiredAt) VALUES (?, ?, ?, ?)';
        $this->context->query($sql, [$userId, $productId, $createdAt, $expiredAt]);
    }

    public function product(int $id)
    {
        $sql = 'SELECT * FROM product WHERE id = ? AND isActive = true';
        $rows = $this->context->query($sql, [$id]);
        return !empty($rows) ? $rows[0] : null;
    }
}
