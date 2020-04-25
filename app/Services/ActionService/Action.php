<?php

namespace Services\ActionService;

use Core\Db\DbContext;
use Core\ServiceContainer;

class Action
{
    public static function check(string $actionName, int $userId)
    {
        if (!self::hasRestrictedProduct($actionName)) {
            return true;
            // because this action not restricted by product
        }

        /** @var DbContext $context */
        $context = ServiceContainer::getInstance()->get('db_context');
        $sql = <<<SQL
SELECT count(a.id) FROM action a 
INNER JOIN productAction pa ON pa.actionId = a.id 
INNER JOIN userProduct up ON up.productId = pa.productId 
WHERE a.name = ? AND up.userId = ? AND up.expiredAt > NOW() 
SQL;
        $count = $context->query($sql, [$actionName, $userId])[0][0];

        return $count > 0;
    }

    public static function hasRestrictedProduct(string $actionName)
    {
        /** @var DbContext $context */
        $context = ServiceContainer::getInstance()->get('db_context');
        $sql = <<<SQL
SELECT count(a.id) FROM action a 
INNER JOIN productAction pa ON pa.actionId = a.id 
INNER JOIN product p ON p.id = pa.productId 
WHERE p.isActive = true AND a.name = ? 
SQL;
        $count = $context->query($sql, [$actionName])[0][0];

        return $count > 0;
    }
}
