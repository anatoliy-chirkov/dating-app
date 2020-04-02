CREATE TABLE entry (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userId int NOT NULL,
    time datetime NOT NULL,
    userAgent varchar(255) NULL
);
