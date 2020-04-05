<?php

namespace Repositories;

use Core\Db\DbContext;
use Core\ServiceContainer;

class TokenRepository
{
    /** @var DbContext  */
    private $dbContext;

    public function __construct()
    {
        $this->dbContext = ServiceContainer::getInstance()->get('db_context');
    }

    public function save(string $token, int $userId)
    {
        $sql = "INSERT INTO token (token, userId) VALUES (?, ?)";
        $this->dbContext->query($sql, [$token, $userId]);
    }

    public function find(string $token)
    {
        $sql = "SELECT userId FROM token WHERE token = ?";
        $rows = $this->dbContext->query($sql, [$token]);
        return empty($rows) ? null : $rows[0];
    }

    public function removeToken(string $token)
    {
        $sql = "DELETE FROM token WHERE token = ?";
        $this->dbContext->query($sql, [$token]);
    }

    public function removeAllUserTokens(int $userId)
    {
        $sql = "DELETE FROM token WHERE userId = ?";
        $this->dbContext->query($sql, [$userId]);
    }
}
