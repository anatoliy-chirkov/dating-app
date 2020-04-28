<?php

namespace Client\Repositories;

use Shared\Core\Db\Exceptions\DbException;
use Shared\Core\Repository;

class MessageRepository extends Repository
{
    public function addMessage(int $chatId, int $authorId, ?string $text)
    {
        $createdAt = date('Y-m-d H:i:s');

        $sql = 'INSERT INTO message (chatId, authorId, text, createdAt) VALUES (?, ?, ?, ?)';
        $this->connection->exec($sql, [$chatId, $authorId, $text, $createdAt]);

        $sql = 'SELECT id, createdAt FROM message WHERE chatId = ? AND authorId = ? AND createdAt = ?';
        return $this->connection->first($sql, [$chatId, $authorId, $createdAt]);
    }

    public function getMessagesByChatId(int $chatId, int $limit, $offset = 0): array
    {
        $sql = <<<SQL
(SELECT m.id, m.text, m.createdAt, m.isRead, u.id as userId, u.name 
FROM message m 
INNER JOIN user u ON u.id = m.authorId 
WHERE chatId = ? 
ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}) ORDER BY id ASC
SQL;

        $rows = $this->connection->all($sql, [$chatId]);

        foreach ($rows as &$row) {
            $sql = 'SELECT id, clientPath, width, height FROM attachment WHERE messageId = ?';
            $attachments = $this->connection->all($sql, [$row['id']]);
            $row['attachment'] = $attachments[0];
        }

        return $rows;
    }

    public function getAllMessagesCount(int $chatId)
    {
        $sql = 'SELECT count(id) FROM message WHERE chatId = ?';
        return $this->connection->value($sql, [$chatId]);
    }

    public function setAllMessagesWasRead(int $chatId, int $userId)
    {
        $sql = 'UPDATE message SET isRead = 1 WHERE chatId = ? AND authorId != ?';
        $this->connection->exec($sql, [$chatId, $userId]);
    }

    public function messageWasRead(int $messageId)
    {
        $sql = 'UPDATE message SET isRead = 1 WHERE id = ?';
        $this->connection->exec($sql, [$messageId]);
    }

    public function getMessageById(int $id)
    {
        $sql = 'SELECT * FROM message WHERE id = ? LIMIT 1';
        return $this->connection->first($sql, [$id]);
    }

    public function getCountNotReadMessages(int $userId)
    {
        $sql = <<<SQL
SELECT count(id) 
FROM message 
WHERE isRead = 0 AND authorId != ? AND chatId IN (SELECT DISTINCT chatId as id FROM chatUser WHERE userId = ?)
SQL;
        return $this->connection->value($sql, [$userId, $userId]);
    }
}
