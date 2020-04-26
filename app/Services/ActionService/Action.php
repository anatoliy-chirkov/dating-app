<?php

namespace Services\ActionService;

use Admin\Repositories\CounterRepository;
use Core\Db\DbContext;
use Core\ServiceContainer;
use Repositories\ProductRepository;

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

    public static function productGroupName(string $actionName)
    {
        /** @var ProductRepository $productRepository */
        $productRepository = ServiceContainer::getInstance()->get('product_repository');
        return $productRepository->getProductGroupNameByAction($actionName);
    }

    public static function counterName(string $actionName)
    {
        /** @var CounterRepository $counterRepository */
        $counterRepository = ServiceContainer::getInstance()->get('counter_repository');
        return $counterRepository->getCounterNameByActionName($actionName);
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

    public static function checkCounter(string $actionName, int $userId, int $value = 1)
    {
        /** @var CounterRepository $counterRepository */
        $counterRepository = ServiceContainer::getInstance()->get('counter_repository');
        $counterData = $counterRepository->getCountDataByActionUser($actionName, $userId);
        $lessThanNullError = false;

        foreach ($counterData as $counterDataItem) {
            if ($counterDataItem['counterActionType'] === 'reduce') {
                $appliedValue = $value * $counterDataItem['counterActionMultiplier'];

                if (($counterDataItem['userCounterCount'] - $appliedValue) < 0) {
                    $lessThanNullError = true;
                }
            }
        }

        return !$lessThanNullError;
    }

    public static function run(string $actionName, int $userId, int $value = 1)
    {
        /** @var CounterRepository $counterRepository */
        $counterRepository = ServiceContainer::getInstance()->get('counter_repository');
        $counterData = $counterRepository->getCountDataByActionUser($actionName, $userId);

        $increaseCounterData = [];
        $reduceCounterData = [];

        foreach ($counterData as $counterDataItem) {
            if ($counterDataItem['counterActionType'] === 'increase') {
                $increaseCounterData[] = $counterDataItem;
            } else {
                $reduceCounterData[] = $counterDataItem;
            }
        }

        $lessThanNullError = false;

        foreach ($reduceCounterData as $reduceCounterDataItem) {
            $appliedValue = $value * $reduceCounterDataItem['counterActionMultiplier'];

            if (($reduceCounterDataItem['userCounterCount'] - $appliedValue) < 0) {
                $lessThanNullError = true;
            }
        }

        if ($lessThanNullError) {
            return false;
        }

        foreach ($reduceCounterData as $reduceCounterDataItem) {
            $appliedValue = $value * $reduceCounterDataItem['counterActionMultiplier'];
            $counterRepository->reduceUserCounter($reduceCounterDataItem['userCounterId'], $appliedValue);
        }

        foreach ($increaseCounterData as $increaseCounterDataItem) {
            $appliedValue = $value * $increaseCounterDataItem['counterActionMultiplier'];

            if ($increaseCounterDataItem['counterActionCounterLimit'] === null) {
                $counterRepository->increaseUserCounter($increaseCounterDataItem['userCounterId'], $appliedValue);
            } else if ($increaseCounterDataItem['userCounterCount'] < $increaseCounterDataItem['counterActionCounterLimit']) {

                if (($increaseCounterDataItem['userCounterCount'] + $appliedValue) < $increaseCounterDataItem['counterActionCounterLimit']) {
                    $counterRepository->increaseUserCounter($increaseCounterDataItem['userCounterId'], $appliedValue);
                } else {
                    $middleValue = $increaseCounterDataItem['counterActionCounterLimit'] - $increaseCounterDataItem['userCounterCount'];
                    $counterRepository->increaseUserCounter($increaseCounterDataItem['userCounterId'], $middleValue);
                }
            }
        }

        return true;
    }
}
