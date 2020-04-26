<?php

namespace Repositories\UserRepository;

use Core\Db\DbContext;
use Core\ServiceContainer;
use Repositories\Helpers\Page;
use Repositories\UserRepository\Model\User;

class UserRepository
{
    private const RESULTS_PER_PAGE_ON_SEARCH = 20;

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
INSERT INTO user (sex, age, name, email, passwordHash, googleGeoId, height, weight, createdAt, lastConnected, raisedAt)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())
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

    public function setTop(int $userId)
    {
        $sql = 'UPDATE user SET isTop = true WHERE id = ?';
        $this->dbContext->query($sql, [$userId]);
    }

    public function unsetTop(int $userId)
    {
        $sql = 'UPDATE user SET isTop = false WHERE id = ?';
        $this->dbContext->query($sql, [$userId]);
    }

    public function raiseProfileInSearch(int $userId)
    {
        $sql = 'UPDATE user SET raisedAt = NOW() WHERE id = ?';
        $this->dbContext->query($sql, [$userId]);
    }

    public function increaseMoney(int $userId, float $amount)
    {
        $sql = "UPDATE user SET money = money + ? WHERE id = ?";
        $this->dbContext->query($sql, [$amount, $userId]);
    }

    public function reduceMoney(int $userId, float $amount)
    {
        $sql = "UPDATE user SET money = money - ? WHERE id = ?";
        $this->dbContext->query($sql, [$amount, $userId]);
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

    public function search(array $sex = [], int $ageFrom = null, int $ageTo = null, array $googleGeoId = null, int $page = 1)
    {
        $sql = <<<SQL
SELECT user.*, g.fullName AS city, CASE WHEN image.clientPath is NULL THEN '/img/default.jpg' ELSE image.clientPath END AS clientPath 
FROM user 
LEFT JOIN image ON image.userId = user.id AND image.isMain = 1 
LEFT JOIN googleGeo AS g ON g.id = user.googleGeoId 
SQL;
        $sql = $this->addSQLWhereStatementToSearch($sql, $sex, $ageFrom, $ageTo, $googleGeoId);
        $sql .= ' ' . 'ORDER BY isTop DESC, raisedAt DESC';
        $sql .= ' ' . (new Page($page, self::RESULTS_PER_PAGE_ON_SEARCH))->getSql();

        return $this->dbContext->query($sql);
    }

    public function count(array $sex = [], int $ageFrom = null, int $ageTo = null, array $googleGeoId = null): int
    {
        $sql = <<<SQL
SELECT count(user.id) 
FROM user 
SQL;

        if ($googleGeoId !== null) {
            $sql .= ' ' . 'INNER JOIN googleGeo g ON g.id = user.googleGeoId';
        }

        $sql = $this->addSQLWhereStatementToSearch($sql, $sex, $ageFrom, $ageTo, $googleGeoId);

        return $this->dbContext->query($sql)[0][0];
    }

    private function addSQLWhereStatementToSearch(string $sql, array $sex = [], int $ageFrom = null, int $ageTo = null, array $googleGeoId = null)
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
            $sql .= ' ' . "g.id IN ({$googleGeoIdIN}) OR g.parentId IN ({$googleGeoIdIN})";
        }

        return $sql;
    }

    private function addSQLOperatorAND(string $sql)
    {
        if (preg_match('/WHERE \w+/', $sql) === 1) {
            $sql .= ' ' . 'AND';
        }

        return $sql;
    }
}
