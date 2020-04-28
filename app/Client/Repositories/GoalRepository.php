<?php

namespace Client\Repositories;

use Shared\Core\Repository;

class GoalRepository extends Repository
{
    public function getAll()
    {
        $sql = 'SELECT * FROM goal';
        return $this->connection->all($sql);
    }

    public function removeUserGoals(int $userId)
    {
        $sql = 'DELETE FROM userGoal WHERE userId = ?';
        $this->connection->exec($sql, [$userId]);
    }

    public function saveUserGoal(int $userId, int $goalId)
    {
        $sql = 'INSERT INTO userGoal (userId, goalId) VALUES (?, ?)';
        $this->connection->exec($sql, [$userId, $goalId]);
    }

    public function getUserGoals(int $userId)
    {
        $sql = <<<SQL
SELECT g.id, g.name 
FROM userGoal ug 
INNER JOIN goal g ON g.id = ug.goalId 
WHERE ug.userId = ?
SQL;
        return $this->connection->all($sql, [$userId]);
    }
}
