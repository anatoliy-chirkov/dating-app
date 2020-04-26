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

    public function getProductGroupNameByAction(string $actionName): string
    {
        $sql = <<<SQL
SELECT pg.name FROM action a 
INNER JOIN productAction pa ON pa.actionId = a.id 
INNER JOIN product p ON p.id = pa.productId 
INNER JOIN productGroup pg ON pg.id = p.groupId 
WHERE a.name = ? AND p.isActive = true 
LIMIT 1 
SQL;
        $rows = $this->context->query($sql, [$actionName]);
        return !empty($rows) ? $rows[0]['name'] : '';
    }

    public function getProductGroups()
    {
        $sql = 'SELECT id, name, about FROM productGroup WHERE isActive = true';
        return $this->context->query($sql);
    }

    public function getProductsByGroup(int $groupId)
    {
        $sql = 'SELECT id, name, price, duration FROM product WHERE groupId = ? AND isActive = true AND isFree = false';
        return $this->context->query($sql, [$groupId]);
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

    public function getProductCommands(int $productId)
    {
        $sql = 'SELECT c.name FROM productCommand pc INNER JOIN command c ON c.id = pc.commandId WHERE productId = ?';
        return $this->context->query($sql, [$productId]);
    }
}
