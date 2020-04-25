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

    public function getUserProducts(int $userId)
    {
        $sql = <<<SQL
SELECT p.id, p.name, pg.name as groupName, p.price, up.expiredAt, up.createdAt FROM product p 
INNER JOIN userProduct up ON up.productId = p.id 
INNER JOIN productGroup pg ON pg.id = p.groupId 
WHERE up.userId = ? AND up.expiredAt > NOW() 
SQL;
        return $this->context->query($sql, [$userId]);
    }

    public function getActiveUserProductByGroup(int $userId, int $groupId)
    {
        $sql = <<<SQL
SELECT * FROM userProduct up 
INNER JOIN product p ON p.id = up.productId 
WHERE p.groupId = ? AND up.userId = ? AND up.expiredAt > NOW() 
ORDER BY expiredAt DESC LIMIT 1
SQL;
        $rows =  $this->context->query($sql, [$groupId, $userId]);
        return empty($rows) ? null : $rows[0];
    }
}