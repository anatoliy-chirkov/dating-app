CREATE TABLE user (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name varchar(40) NOT NULL,
    email varchar(60) NOT NULL,
    passwordHash varchar(64) NOT NULL,
    placeId varchar(40) NOT NULL,
    age int NULL,
    weight int NULL,
    height int NULL,
    about text NULL,
    sex varchar(20) NOT NULL,
    money int NOT NULL default(0),
    createdAt datetime NOT NULL,
    isConnected tinyint NOT NULL default(0),
    lastConnected datetime NOT NULL
);
