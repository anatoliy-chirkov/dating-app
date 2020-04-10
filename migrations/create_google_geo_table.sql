CREATE TABLE googleGeo (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    type varchar(20) NOT NULL,
    name varchar(255) NOT NULL,
    fullName varchar(255) NOT NULL,
    parentId int NULL,
    placeId varchar(255) NULL,
    lat double NULL,
    lng double NULL
);
