<?php

namespace Repositories\UserRepository;

use Core\Db\DbContext;
use Core\ServiceContainer;
use Repositories\UserRepository\Filter\Page;
use Repositories\UserRepository\Model\User;

class UserRepository
{
    /** @var DbContext */
    private $dbContext;

    public function __construct()
    {
        $this->dbContext = ServiceContainer::getInstance()->get('db_context');
    }

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
        $rows = $this->dbContext->query($sql, [$token]);

        return !empty($rows) ? $rows[0] : null;
    }

    public function update(int $userId, string $name, int $age, int $googleGeoId, ?int $height, ?int $weight, ?string $about)
    {
        $sql = 'UPDATE user SET name = ?, age = ?, googleGeoId = ?, height = ?, weight = ?, about = ? WHERE id = ?';
        $this->dbContext->query($sql, [$name, $age, $googleGeoId, $height, $weight, $about, $userId]);
    }

    public function createUser(User $user)
    {
        $sql = <<<SQL
INSERT INTO user (sex, age, name, email, passwordHash, googleGeoId, height, weight, createdAt, lastConnected)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
SQL;
        $this->dbContext->query($sql, [
            $user->sex,
            $user->age,
            $user->name,
            $user->email,
            $user->passwordHash,
            $user->googleGeoId,
            $user->height,
            $user->weight,
        ]);
        return;
    }

    public function setOnline(int $userId)
    {
        $sql = "UPDATE user SET isConnected = 1, lastConnected = NOW() WHERE id = ?";
        $this->dbContext->query($sql, [$userId]);
    }

    public function setOffline(int $userId)
    {
        $sql = "UPDATE user SET isConnected = 0 WHERE id = ?";
        $this->dbContext->query($sql, [$userId]);
    }

    public function setTemporaryOnline(int $userId)
    {
        $sql = "UPDATE user SET lastConnected = NOW() WHERE id = ?";
        $this->dbContext->query($sql, [$userId]);
    }

    public function setNewPasswordHash(int $userId, string $passwordHash)
    {
        $sql = "UPDATE user SET passwordHash = ? WHERE id = ?";
        $this->dbContext->query($sql, [$passwordHash, $userId]);
    }

    public function isExist(string $email)
    {
        $sql = "SELECT count(id) FROM user WHERE email = ?";
        $count = $this->dbContext->query($sql, [$email])[0][0];
        return $count > 0;
    }

    public function getIdByEmail(string $email): int
    {
        $sql = "SELECT id FROM user WHERE email = ? LIMIT 1";
        $rows = $this->dbContext->query($sql, [$email]);
        return empty($rows) ? null : $rows[0]['id'];
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
        $rows = $this->dbContext->query($sql, [$email]);
        return empty($rows) ? null : $rows[0];
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
        $rows = $this->dbContext->query($sql, [$userId]);
        return empty($rows) ? [] : $rows[0];
    }

    public function search(array $sex = null, int $ageFrom = null, int $ageTo = null, int $googleGeoId = null, int $page = 1)
    {
        $sql = <<<SQL
SELECT user.*, g.fullName AS city, CASE WHEN image.clientPath is NULL THEN '/img/default.jpg' ELSE image.clientPath END AS clientPath 
FROM user 
LEFT JOIN image ON image.userId = user.id AND image.isMain = 1 
LEFT JOIN googleGeo AS g ON g.id = user.googleGeoId 
SQL;
        $sql = $this->addSQLWhereStatementToSearch($sql, $sex, $ageFrom, $ageTo, $googleGeoId);
        $sql .= ' ' . (new Page($page))->getSql();

        return $this->dbContext->query($sql);
    }

    public function count(array $sex = null, int $ageFrom = null, int $ageTo = null, int $googleGeoId = null): int
    {
        $sql = <<<SQL
SELECT count(id) 
FROM user 
SQL;

        if ($googleGeoId !== null) {
            $sql .= ' ' . 'INNER JOIN googleGeo g ON g.id = user.googleGeoId';
        }

        $sql .= $this->addSQLWhereStatementToSearch($sql, $sex, $ageFrom, $ageTo, $googleGeoId);

        return $this->dbContext->query($sql)[0][0];
    }

    private function addSQLWhereStatementToSearch(string $sql, array $sex = null, int $ageFrom = null, int $ageTo = null, int $googleGeoId = null)
    {
        if ((array_search('man', $sex) !== false || array_search('woman', $sex) !== false) || $ageFrom || $ageTo || $googleGeoId) {
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
            if (preg_match('/WHERE \w+/', $sql) === 1) {
                $sql .= ' ' . 'AND';
            }

            $sql .= ' ' . "age BETWEEN {$ageFrom} AND {$ageTo}";
        } else if ($ageFrom !== null) {
            if (preg_match('/WHERE \w+/', $sql) === 1) {
                $sql .= ' ' . 'AND';
            }

            $sql .= ' ' . "age >= {$ageFrom}";
        } else if ($ageTo !== null) {
            if (preg_match('/WHERE \w+/', $sql) === 1) {
                $sql .= ' ' . 'AND';
            }

            $sql .= ' ' . "age <= {$ageTo}";
        }
        if ($googleGeoId !== null) {
            if (preg_match('/WHERE \w+/', $sql) === 1) {
                $sql .= ' ' . 'AND';
            }

            $sql .= ' ' . "g.id = {$googleGeoId}";
        }

        return $sql;
    }
}
