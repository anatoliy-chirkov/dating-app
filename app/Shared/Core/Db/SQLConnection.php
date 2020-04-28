<?php

namespace Shared\Core\Db;

use Shared\Core\App;
use Shared\Core\Db\Exceptions\DbException;
use PDOStatement;
use PDO;

class SQLConnection
{
    /** @var PDO */
    private $dbh;

    public function __construct()
    {
        $host   = App::get('env')->get('DB_HOST');
        $port   = App::get('env')->get('DB_PORT');
        $dbName = App::get('env')->get('DB_NAME');
        $user   = App::get('env')->get('DB_USERNAME');
        $pass   = App::get('env')->get('DB_PASSWORD');

        $dsn = "mysql:dbname={$dbName};host={$host}:{$port}";
        $this->dbh = new PDO($dsn, $user, $pass);
    }

    public function exec(string $sql, array $params = []): PDOStatement
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($params);

        if (!empty($sth->errorInfo()[2])) {
            throw new DbException($sth->errorInfo()[2]);
        }

        return $sth;
    }

    public function first(string $sql, array $params = [])
    {
        $sth = $this->exec($sql, $params);
        $result =  $sth->fetch(PDO::FETCH_ASSOC);
        return $result === false ? null : $result;
    }

    public function all(string $sql, array $params = [])
    {
        $sth = $this->exec($sql, $params);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function value(string $sql, array $params = [])
    {
        $sth = $this->exec($sql, $params);
        $result = $sth->fetch(PDO::FETCH_COLUMN|0);
        return $result === false ? null : $result;
    }
}
