<?php

namespace Admin\Repositories;

use Core\Repository;

class ProductRepository extends Repository
{
    public function productGroups()
    {
        $sql = 'SELECT * FROM productGroup';
        return $this->context->query($sql);
    }

    public function productGroup(int $id)
    {
        $sql = 'SELECT * FROM productGroup WHERE id = ?';
        $rows = $this->context->query($sql, [$id]);
        return !empty($rows) ? $rows[0] : null;
    }

    public function addProductGroup(string $name, string $about, bool $isActive)
    {
        $sql = 'INSERT INTO productGroup (name, about, isActive) VALUES (?, ?, ?)';
        $this->context->query($sql, [$name, $about, $isActive]);
    }

    public function editProductGroup(int $id, string $name, string $about, bool $isActive)
    {
        $sql = 'UPDATE productGroup SET name = ?, about = ?, isActive = ? WHERE id = ?';
        $this->context->query($sql, [$name, $about, $isActive, $id]);
    }

    public function product(int $id)
    {
        $sql = 'SELECT * FROM product WHERE id = ?';
        $rows = $this->context->query($sql, [$id]);
        return !empty($rows) ? $rows[0] : null;
    }

    public function products()
    {
        $sql = 'SELECT p.*, g.name as groupName FROM product p INNER JOIN productGroup g ON g.id = p.groupId';
        return $this->context->query($sql);
    }

    public function productsByGroup(int $groupId)
    {
        $sql = 'SELECT * FROM product WHERE groupId = ?';
        return $this->context->query($sql, [$groupId]);
    }

    public function notFreeProducts()
    {
        $sql = 'SELECT * FROM product WHERE isFree = false';
        return $this->context->query($sql);
    }

    public function freeProducts()
    {
        $sql = 'SELECT * FROM product WHERE isFree = true';
        return $this->context->query($sql);
    }

    public function addProduct(string $name, string $type, int $groupId, int $duration, float $price, bool $isFree, bool $isActive): int
    {
        $sql = 'INSERT INTO product (name, type, groupId, duration, price, isFree, isActive) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $this->context->query($sql, [$name, $type, $groupId, $duration, $price, $isFree, $isActive]);

        $sql = 'SELECT id FROM product ORDER BY id DESC LIMIT 1';
        return $this->context->query($sql)[0]['id'];
    }

    public function updateProduct(int $id, string $name, string $type, int $groupId, int $duration, float $price, bool $isFree, bool $isActive)
    {
        $sql = <<<SQL
UPDATE product 
SET name = ?, type = ?, groupId = ?, duration = ?, price = ?, isFree = ?, isActive = ? 
WHERE id = ? 
SQL;
        $this->context->query($sql, [$name, $type, $groupId, $duration, $price, $isFree, $isActive, $id]);
    }

    // ACTIONS
    public function actions()
    {
        $sql = 'SELECT * FROM action';
        return $this->context->query($sql);
    }

    public function productActions(int $productId)
    {
        $sql = 'SELECT * FROM productAction WHERE productId = ?';
        return $this->context->query($sql, [$productId]);
    }

    public function removeProductAction(int $id)
    {
        $sql = 'DELETE FROM productAction WHERE id = ?';
        $this->context->query($sql, [$id]);
    }

    public function removeProductActions(int $productId)
    {
        $sql = 'DELETE FROM productAction WHERE productId = ?';
        $this->context->query($sql, [$productId]);
    }

    public function addProductAction(int $productId, int $actionId)
    {
        $sql = 'INSERT INTO productAction (productId, actionId) VALUES (?, ?)';
        $this->context->query($sql, [$productId, $actionId]);
    }

    // COMMANDS
    public function commands()
    {
        $sql = 'SELECT * FROM command';
        return $this->context->query($sql);
    }

    public function productCommands(int $productId)
    {
        $sql = 'SELECT * FROM productCommand WHERE productId = ?';
        return $this->context->query($sql, [$productId]);
    }

    public function removeProductCommand(int $id)
    {
        $sql = 'DELETE FROM productCommand WHERE id = ?';
        $this->context->query($sql, [$id]);
    }

    public function removeProductCommands(int $productId)
    {
        $sql = 'DELETE FROM productCommand WHERE productId = ?';
        $this->context->query($sql, [$productId]);
    }

    public function addProductCommand(int $productId, int $commandId)
    {
        $sql = 'INSERT INTO productCommand (productId, commandId) VALUES (?, ?)';
        $this->context->query($sql, [$productId, $commandId]);
    }
}
