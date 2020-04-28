<?php

namespace Admin\Repositories;

use Shared\Core\Repository;

class LogRepository extends Repository
{
    public function search(int $offset = 0, int $length = 10)
    {
        $sql = 'SELECT * FROM log';
        $sql .= ' ' . 'ORDER BY id DESC';
        $sql .= ' ' . "LIMIT {$length}";

        if ($offset > 0) {
            $sql .= ' ' . "OFFSET {$offset}";
        }

        return $this->connection->all($sql);
    }

    public function count()
    {
        $sql = 'SELECT count(id) FROM log';
        return $this->connection->value($sql);
    }
}
