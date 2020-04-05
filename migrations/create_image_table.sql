CREATE TABLE image (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userId int NOT NULL,
    serverPath varchar(255) NOT NULL,
    clientPath varchar(255) NOT NULL,
    isMain tinyint(1) NOT NULL DEFAULT(0),
    createdAt datetime NOT NULL
);
