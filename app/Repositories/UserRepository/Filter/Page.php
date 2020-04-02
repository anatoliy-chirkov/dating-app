<?php

namespace Repositories\UserRepository\Filter;

class Page implements ISql
{
    private const LIMIT = 20;

    private $page;

    public function __construct(int $page)
    {
        $this->page = $page > 0 ? $page : 1;
    }

    public function getSql()
    {
        $offsetSql = null;

        if ($this->page > 1) {
            $offsetSql = ' ' . 'OFFSET' . ' ' . ($this->page - 1) * 3;
        }

        return 'LIMIT' . ' ' . self::LIMIT . $offsetSql;
    }
}
