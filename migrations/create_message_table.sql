CREATE TABLE message (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    chatId int NOT NULL,
    authorId int NOT NULL,
    text text NULL,
    createdAt datetime NOT NULL,
    isRead tinyint NOT NULL default(0)
);
