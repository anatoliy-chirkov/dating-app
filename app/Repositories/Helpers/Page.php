<?php

namespace Repositories\Helpers;

class Page implements ISql
{
    private $page;
    private $resultsPerPage;

    public function __construct(int $page, int $resultsPerPage)
    {
        $this->page = $page > 0 ? $page : 1;
        $this->resultsPerPage = $resultsPerPage;
    }

    public function getSql()
    {
        $offsetSql = null;

        if ($this->page > 1) {
            $offsetSql = ' ' . 'OFFSET' . ' ' . ($this->page - 1) * $this->resultsPerPage;
        }

        return 'LIMIT' . ' ' . $this->resultsPerPage . $offsetSql;
    }
}
