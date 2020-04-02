CREATE TABLE token (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userId int NOT NULL,
    token varchar(32) NOT NULL
);
