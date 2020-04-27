<?php

namespace Admin\Repositories;

use Core\Repository;

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

        return $this->context->query($sql);
    }

    public function count()
    {
        $sql = 'SELECT count(id) FROM log';
        return $this->context->query($sql)[0][0];
    }
}
