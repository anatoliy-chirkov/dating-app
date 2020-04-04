<?php


namespace Repositories;


use Core\Db\DbContext;
use Core\Db\Exceptions\DbException;
use Core\ServiceContainer;

class MessageRepository
{
    /** @var DbContext */
    private $dbContext;

    public function __construct()
    {
        $this->dbContext = ServiceContainer::getInstance()->get('db_context');
    }

    public function addMessage(int $chatId, int $authorId, string $text)
    {
        $createdAt = date('Y-m-d H:i:s');

        $sql = 'INSERT INTO message (chatId, authorId, text, createdAt) VALUES (?, ?, ?, ?)';
        $this->dbContext->query($sql, [$chatId, $authorId, $text, $createdAt]);

        $sql = 'SELECT id, DATE_FORMAT(createdAt, \'%d %b %H:%i\') as createdAt FROM message WHERE chatId = ? AND authorId = ? AND createdAt = ?';
        $rows = $this->dbContext->query($sql, [$chatId, $authorId, $createdAt]);

        if (empty($rows)) {
            throw new DbException('Message not found after save');
        }

        return $rows[0];
    }

    public function getMessagesByChatId(int $chatId): array
    {
        $sql = <<<SQL
SELECT m.id, m.text, DATE_FORMAT(m.createdAt, '%d %b %H:%i') as createdAt, u.id as userId, u.name 
FROM message m 
INNER JOIN user u on u.id = m.authorId 
WHERE chatId = ?
SQL;

        return $this->dbContext->query($sql, [$chatId]);
    }

    public function setAllMessagesWasRead(int $chatId, int $userId)
    {
        $sql = 'UPDATE message SET isRead = 1 WHERE chatId = ? AND authorId != ?';
        $this->dbContext->query($sql, [$chatId, $userId]);
    }

    public function messageWasRead(int $messageId)
    {
        $sql = 'UPDATE message SET isRead = 1 WHERE id = ?';
        $this->dbContext->query($sql, [$messageId]);
    }

    public function getMessageById(int $id)
    {
        $sql = 'SELECT * FROM message WHERE id = ? LIMIT 1';
        $rows = $this->dbContext->query($sql, [$id]);

        return !empty($rows) ? $rows[0] : null;
    }

    public function getCountNotReadMessages(int $userId)
    {
        $sql = <<<SQL
SELECT count(id) 
FROM message 
WHERE isRead = 0 AND authorId != ? AND chatId IN (SELECT DISTINCT chatId as id FROM chatUser WHERE userId = ?)
SQL;

        return $this->dbContext->query($sql, [$userId, $userId])[0][0];
    }
}
