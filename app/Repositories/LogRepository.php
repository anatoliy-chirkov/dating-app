<?php

namespace Repositories;

use Core\Repository;

class LogRepository extends Repository
{
    public function log(string $uri, ?int $code, ?string $message)
    {
        $sql = 'INSERT INTO log (uri, code, message, createdAt) VALUES (?, ?, ?, NOW())';
        $this->context->query($sql, [$uri, $code, $message]);
    }
}
