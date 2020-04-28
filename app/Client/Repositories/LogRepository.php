<?php

namespace Client\Repositories;

use Shared\Core\Repository;

class LogRepository extends Repository
{
    public function log(string $uri, ?int $code, ?string $message)
    {
        $sql = 'INSERT INTO log (uri, code, message, createdAt) VALUES (?, ?, ?, NOW())';
        $this->connection->exec($sql, [$uri, $code, $message]);
    }
}
