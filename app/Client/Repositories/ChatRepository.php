<?php

namespace Client\Repositories;

use Shared\Core\Repository;

class ChatRepository extends Repository
{
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
        $this->connection->exec($sql, [$hash]);

        $sql = 'SELECT id FROM chat WHERE hash = ? LIMIT 1';
        $chatId = $this->connection->value($sql, [$hash]);

        $sql = 'INSERT INTO chatUser (chatId, userId) VALUES (?, ?)';

        foreach ($users as $userId) {
            $this->connection->exec($sql, [$chatId, $userId]);
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

        return $this->connection->value($sql, [$users[0], $users[1]]);
    }

    public function getChatsByUserId(int $userId): array
    {
        $sql = <<<SQL
SELECT DISTINCT chatUser.userId, chatUser.chatId, user.name, user.age, user.lastConnected, user.isConnected, 
CASE WHEN image.clientPath is NULL THEN '/img/default.jpg' ELSE image.clientPath END AS clientPath
FROM chatUser 
INNER JOIN (SELECT DISTINCT chatId FROM chatUser WHERE userId = ?) t ON t.chatId = chatUser.chatId 
INNER JOIN user ON user.id = chatUser.userId 
LEFT JOIN image ON image.userId = user.id AND image.isMain = 1 
WHERE chatUser.userId != ? 
SQL;

        $rows = $this->connection->all($sql, [$userId, $userId]);

        foreach ($rows as &$row) {
            $sql = "SELECT count(id) FROM message WHERE chatId = ? AND isRead = 0 AND authorId != ?";
            $notReadCount = $this->connection->value($sql, [$row['chatId'], $userId]);

            $sql = "SELECT text, createdAt, isRead, authorId FROM message WHERE chatId = ? ORDER BY createdAt DESC LIMIT 1";
            $messageRows = $this->connection->all($sql, [$row['chatId']]);
            $row['text'] = !empty($messageRows) ? $messageRows[0]['text'] : null;
            $row['createdAt'] = !empty($messageRows) ? $messageRows[0]['createdAt'] : null;
            $row['authorId'] = !empty($messageRows) ? $messageRows[0]['authorId'] : null;
            $row['isRead'] = !empty($messageRows) ? $messageRows[0]['isRead'] : null;
            $row['notReadCount'] = $notReadCount;
        }

        usort($rows, static function ($a, $b) {
            if ($a['createdAt'] === $b['createdAt']) {
                return 0;
            }

            return ($a['createdAt'] > $b['createdAt']) ? -1 : 1;
        });

        return $rows;
    }
}
