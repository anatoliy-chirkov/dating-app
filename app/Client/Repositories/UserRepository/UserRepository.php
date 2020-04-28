<?php

namespace Client\Repositories\UserRepository;

use Client\Repositories\Helpers\Page;
use Client\Repositories\UserRepository\Model\User;
use Shared\Core\Repository;

class UserRepository extends Repository
{
    private const RESULTS_PER_PAGE_ON_SEARCH = 20;

    public function getUserByToken(string $token)
    {
        $sql = <<<SQL
SELECT user.*, g.fullName as city, 
CASE WHEN image.clientPath is NULL THEN '/img/default.jpg' ELSE image.clientPath END AS clientPath
FROM user 
LEFT JOIN image ON image.userId = user.id AND image.isMain = 1 
LEFT JOIN token ON token.userId = user.id 
LEFT JOIN googleGeo g ON g.id = user.googleGeoId 
WHERE token.token = ? 
LIMIT 1 
SQL;
        return $this->connection->first($sql, [$token]);
    }

    public function update(int $userId, string $name, int $age, int $googleGeoId, ?int $height, ?int $weight, ?string $about)
    {
        $sql = 'UPDATE user SET name = ?, age = ?, googleGeoId = ?, height = ?, weight = ?, about = ? WHERE id = ?';
        $this->connection->exec($sql, [$name, $age, $googleGeoId, $height, $weight, $about, $userId]);
    }

    public function createUser(User $user)
    {
        $sql = <<<SQL
INSERT INTO user (sex, age, name, email, passwordHash, googleGeoId, height, weight, createdAt, lastConnected, raisedAt)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())
SQL;
        $this->connection->exec($sql, [
            $user->sex,
            $user->age,
            $user->name,
            $user->email,
            $user->passwordHash,
            $user->googleGeoId,
            $user->height,
            $user->weight,
        ]);
    }

    public function setTop(int $userId)
    {
        $sql = 'UPDATE user SET isTop = true WHERE id = ?';
        $this->connection->exec($sql, [$userId]);
    }

    public function unsetTop(int $userId)
    {
        $sql = 'UPDATE user SET isTop = false WHERE id = ?';
        $this->connection->exec($sql, [$userId]);
    }

    public function raiseProfileInSearch(int $userId)
    {
        $sql = 'UPDATE user SET raisedAt = NOW() WHERE id = ?';
        $this->connection->exec($sql, [$userId]);
    }

    public function increaseMoney(int $userId, float $amount)
    {
        $sql = "UPDATE user SET money = money + ? WHERE id = ?";
        $this->connection->exec($sql, [$amount, $userId]);
    }

    public function reduceMoney(int $userId, float $amount)
    {
        $sql = "UPDATE user SET money = money - ? WHERE id = ?";
        $this->connection->exec($sql, [$amount, $userId]);
    }

    public function setOnline(int $userId)
    {
        $sql = "UPDATE user SET isConnected = 1, lastConnected = NOW() WHERE id = ?";
        $this->connection->exec($sql, [$userId]);
    }

    public function setOffline(int $userId)
    {
        $sql = "UPDATE user SET isConnected = 0 WHERE id = ?";
        $this->connection->exec($sql, [$userId]);
    }

    public function setTemporaryOnline(int $userId)
    {
        $sql = "UPDATE user SET lastConnected = NOW() WHERE id = ?";
        $this->connection->exec($sql, [$userId]);
    }

    public function setNewPasswordHash(int $userId, string $passwordHash)
    {
        $sql = "UPDATE user SET passwordHash = ? WHERE id = ?";
        $this->connection->exec($sql, [$passwordHash, $userId]);
    }

    public function isExist(string $email)
    {
        $sql = "SELECT count(id) FROM user WHERE email = ?";
        $count = $this->connection->value($sql, [$email])[0][0];
        return $count > 0;
    }

    public function getIdByEmail(string $email): int
    {
        $sql = "SELECT id FROM user WHERE email = ? LIMIT 1";
        return $this->connection->value($sql, [$email]);
    }

    public function getByEmail(string $email)
    {
        $sql = <<<SQL
SELECT user.*, g.fullName as city, CASE WHEN image.clientPath is NULL THEN '/img/default.jpg' ELSE image.clientPath END AS clientPath 
FROM user 
LEFT JOIN image ON image.userId = user.id AND image.isMain = 1 
LEFT JOIN googleGeo g ON g.id = user.googleGeoId 
WHERE user.email = ? LIMIT 1
SQL;
        return $this->connection->first($sql, [$email]);
    }

    public function getById(int $userId)
    {
        $sql = <<<SQL
SELECT user.*, g.fullName as city, CASE WHEN image.clientPath is NULL THEN '/img/default.jpg' ELSE image.clientPath END AS clientPath 
FROM user 
LEFT JOIN image ON image.userId = user.id AND image.isMain = 1 
LEFT JOIN googleGeo g ON g.id = user.googleGeoId 
WHERE user.id = ? LIMIT 1
SQL;
        return $this->connection->first($sql, [$userId]);
    }

    public function search(array $sex = [], int $ageFrom = null, int $ageTo = null, array $googleGeoId = null, array $goalsId = null, int $page = 1, int $resultsPerPage = self::RESULTS_PER_PAGE_ON_SEARCH)
    {
        $sql = <<<SQL
SELECT user.*, g.fullName AS city, CASE WHEN image.clientPath is NULL THEN '/img/default.jpg' ELSE image.clientPath END AS clientPath 
FROM user 
LEFT JOIN image ON image.userId = user.id AND image.isMain = 1 
LEFT JOIN googleGeo AS g ON g.id = user.googleGeoId 
LEFT JOIN userGoal AS ug ON ug.userId = user.id 
SQL;
        $sql = $this->addSQLWhereStatementToSearch($sql, $sex, $ageFrom, $ageTo, $googleGeoId, $goalsId);
        $sql .= ' ' . 'ORDER BY isTop DESC, raisedAt DESC';
        $sql .= ' ' . (new Page($page, $resultsPerPage))->getSql();

        return $this->connection->all($sql);
    }

    public function count(array $sex = [], int $ageFrom = null, int $ageTo = null, array $googleGeoId = null, array $goalsId = null): int
    {
        $sql = <<<SQL
SELECT count(user.id) 
FROM user 
SQL;

        if ($googleGeoId !== null) {
            $sql .= ' ' . 'INNER JOIN googleGeo g ON g.id = user.googleGeoId';
        }

        if ($goalsId !== null) {
            $sql .= ' ' . 'INNER JOIN userGoal ug ON ug.userId = user.id';
        }

        $sql = $this->addSQLWhereStatementToSearch($sql, $sex, $ageFrom, $ageTo, $googleGeoId, $goalsId);

        return $this->connection->value($sql);
    }

    private function addSQLWhereStatementToSearch(string $sql, array $sex = [], int $ageFrom = null, int $ageTo = null, array $googleGeoId = null, array $goalsId = null)
    {
        if ((array_search('man', $sex) !== false || array_search('woman', $sex) !== false) || $ageFrom || $ageTo || $googleGeoId || $goalsId) {
            $sql .= ' ' . 'WHERE';
        }

        if (array_search('man', $sex) !== false || array_search('woman', $sex) !== false) {
            if (array_search('man', $sex) !== false && array_search('woman', $sex) !== false) {
                $sql .= ' ' . "sex IN ('man', 'woman')";
            } else if (array_search('man', $sex) !== false) {
                $sql .= ' ' . "sex = 'man'";
            } else if (array_search('woman', $sex) !== false) {
                $sql .= ' ' . "sex = 'woman'";
            }
        }

        if ($ageFrom !== null && $ageTo !== null) {
            $sql = $this->addSQLOperatorAND($sql);
            $sql .= ' ' . "age BETWEEN {$ageFrom} AND {$ageTo}";
        } else if ($ageFrom !== null) {
            $sql = $this->addSQLOperatorAND($sql);
            $sql .= ' ' . "age >= {$ageFrom}";
        } else if ($ageTo !== null) {
            $sql = $this->addSQLOperatorAND($sql);
            $sql .= ' ' . "age <= {$ageTo}";
        }

        if ($googleGeoId !== null) {
            $sql = $this->addSQLOperatorAND($sql);
            $googleGeoIdIN = implode(', ', $googleGeoId);
            $sql .= ' ' . "(g.id IN ({$googleGeoIdIN}) OR g.parentId IN ({$googleGeoIdIN}))";
        }

        if ($goalsId !== null) {
            $sql = $this->addSQLOperatorAND($sql);
            $goalsIdIN = implode(', ', $goalsId);
            $sql .= ' ' . "ug.goalId IN ({$goalsIdIN})";
        }

        return $sql;
    }

    private function addSQLOperatorAND(string $sql)
    {
        if (preg_match('/WHERE [\w|(]/', $sql) === 1) {
            $sql .= ' ' . 'AND';
        }

        return $sql;
    }
}
