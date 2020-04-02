<?php

namespace Repositories;

use Core\Db\DbContext;
use Core\ServiceContainer;

class ChatRepository
{
    /** @var DbContext */
    private $dbContext;

    public function __construct()
    {
        $this->dbContext = ServiceContainer::getInstance()->get('db_context');
    }

    public function createChat(array $users)
    {
        /*
         * VALIDATE PARAMETER BEGIN
         */
            if (count($users) !== 2) {
                throw new \Exception('Must be 2 users for create chat', 500);
            }
            if (!is_int($users[0]) || !is_int($users[1])) {
                throw new \Exception('UsersIds must be integer', 500);
            }
        /*
         * VALIDATE PARAMETER END
         */

        $hash = hash('sha256', implode(',', $users) . time());

        $sql = 'INSERT INTO chat (hash) VALUES (?)';
        $this->dbContext->query($sql, [$hash]);

        $sql = 'SELECT id FROM chat WHERE hash = ? LIMIT 1';
        $rows = $this->dbContext->query($sql, [$hash]);

        $chatId = $rows[0]['id'];

        $sql = 'INSERT INTO chatUser (chatId, userId) VALUES (?, ?)';

        foreach ($users as $userId) {
            $this->dbContext->query($sql, [$chatId, $userId]);
        }

        return $chatId;
    }

    public function getChatIdByUsers(array $users): ?int
    {
        $sql = <<<SQL
SELECT chatId FROM chatUser 
WHERE chatId IN (SELECT DISTINCT chatId FROM chatUser WHERE userId = ?) 
AND userId = ? 
LIMIT 1 
SQL;

        $rows = $this->dbContext->query($sql, [$users[0], $users[1]]);

        return !empty($rows) ? $rows[0]['chatId'] : null;
    }

    public function getChatsByUserId(int $userId): array
    {
        $sql = <<<SQL
SELECT DISTINCT chatUser.userId, chatUser.chatId, user.name, user.age, user.city, image.path 
FROM chatUser 
INNER JOIN (SELECT DISTINCT chatId FROM chatUser WHERE userId = ?) t ON t.chatId = chatUser.chatId 
INNER JOIN user ON user.id = chatUser.userId 
LEFT JOIN image ON image.userId = user.id AND image.isMain = 1 
WHERE chatUser.userId != ? 
SQL;

        $rows = $this->dbContext->query($sql, [$userId, $userId]);

        foreach ($rows as &$row) {
            $sql = "SELECT text FROM message WHERE chatId = ? ORDER BY createdAt DESC LIMIT 1";
            $messageRows = $this->dbContext->query($sql, [$row['chatId']]);
            $row['text'] = !empty($messageRows) ? $messageRows[0]['text'] : null;
        }

        return $rows;
    }
}
