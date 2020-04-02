<?php


namespace Repositories;


use Core\Db\DbContext;
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
        $sql = 'INSERT INTO message (chatId, authorId, text, createdAt) VALUES (?, ?, ?, NOW())';
        $this->dbContext->query($sql, [$chatId, $authorId, $text]);
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
}
