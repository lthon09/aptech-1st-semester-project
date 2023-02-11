CREATE DATABASE IF NOT EXISTS PleasantTours;
USE PleasantTours;

CREATE TABLE IF NOT EXISTS Members (
    ID CHAR(16),

    Username VARCHAR(20) NOT NULL,
    `Password` CHAR(60) NOT NULL,

    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS Categories (
    ID CHAR(16),

    `Name` VARCHAR(100) NOT NULL,

    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS Countries (
    ID CHAR(16),

    `Name` VARCHAR(100) NOT NULL,

    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS Tours (
    ID CHAR(16),

    `Name` VARCHAR(100) NOT NULL,

    ShortDescription VARCHAR(200) NOT NULL,
    LongDescription VARCHAR(1000) NOT NULL,

    Price DECIMAL(15, 2) NOT NULL,
    Sale INT DEFAULT 0,

    Country CHAR(16),

    Avatar VARCHAR(25) NOT NULL,

    Category CHAR(16),

    `From` DATETIME NOT NULL,
    `To` DATETIME NOT NULL,

    Document VARCHAR(25) NOT NULL,

    PRIMARY KEY (ID),

    FOREIGN KEY (Country) REFERENCES Countries(ID),
    FOREIGN KEY (Category) REFERENCES Categories(ID),

    CHECK (0 >= Sale <= 100)
);

CREATE TABLE IF NOT EXISTS Reviews (
    ID CHAR(16),

    Tour CHAR(16),

    Author CHAR(16),

    Content VARCHAR(1000),
    Rating TINYINT UNSIGNED NOT NULL,

    PRIMARY KEY (ID),

    FOREIGN KEY (Tour) REFERENCES Tours(ID),
    FOREIGN KEY (Author) REFERENCES Members(ID),

    CHECK (Rating <= 5)
);