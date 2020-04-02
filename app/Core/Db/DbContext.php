<?php

namespace Core\Db;

use Core\Db\Exceptions\DbException;

class DbContext
{
    private $dbh;

    public function __construct($host, $port, $dbName, $user, $password)
    {
        $dsn = "mysql:dbname={$dbName};host={$host}:{$port}";

        $this->dbh = new \PDO($dsn, $user, $password);
    }

    public function query(string $sql, array $params = [])
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($params);

        if (!empty($sth->errorInfo()[2])) {
            throw new DbException($sth->errorInfo()[2]);
        }

        return $sth->fetchAll();
    }
}
