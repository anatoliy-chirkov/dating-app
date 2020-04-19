<?php

namespace Repositories;

use Core\Db\DbContext;
use Core\ServiceContainer;

class GoalRepository
{
    /** @var DbContext */
    private $dbContext;

    public function __construct()
    {
        $this->dbContext = ServiceContainer::getInstance()->get('db_context');
    }

    public function getAll()
    {
        $sql = 'SELECT * FROM goal';
        return $this->dbContext->query($sql);
    }

    public function saveUserGoal(int $userId, int $goalId)
    {
        $sql = 'INSERT INTO userGoal (userId, goalId) VALUES (?, ?)';
        $this->dbContext->query($sql, [$userId, $goalId]);
    }

    public function getUserGoals(int $userId)
    {
        $sql = <<<SQL
SELECT g.id, g.name 
FROM userGoal ug 
INNER JOIN goal g ON g.id = ug.goalId 
WHERE ug.userId = ?
SQL;
        $this->dbContext->query($sql, [$userId]);
    }
}
