<?php

namespace Core;

use Core\Db\DbContext;

abstract class Repository
{
    /** @var DbContext $context */
    protected $context;

    public function __construct()
    {
        $this->context = ServiceContainer::getInstance()->get('db_context');
    }
}
