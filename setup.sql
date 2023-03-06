SET GLOBAL event_scheduler = ON;

CREATE DATABASE IF NOT EXISTS PleasantTours;
USE PleasantTours;

CREATE TABLE IF NOT EXISTS UnverifiedMembers (
    ID CHAR(32),

    Username VARCHAR(20) NOT NULL,
    `Password` CHAR(97) NOT NULL,

    Email VARCHAR(255) NOT NULL,

    Expiration DATETIME DEFAULT ADDTIME(NOW(), 900), -- 900 seconds = 15 minutes

    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS Members (
    Username VARCHAR(20) NOT NULL,
    `Password` CHAR(97) NOT NULL,

    Email VARCHAR(255) NOT NULL,

    Administrator BOOLEAN NOT NULL, 

    PRIMARY KEY (Username)
);

CREATE TABLE IF NOT EXISTS ResetPasswordMembers (
    ID CHAR(32),

    Member VARCHAR(20) NOT NULL,

    Expiration DATETIME DEFAULT ADDTIME(NOW(), 900), -- 900 seconds = 15 minutes

    PRIMARY KEY (ID),

    FOREIGN KEY (Member) REFERENCES Members(Username)
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
    DetailedInformations TEXT NOT NULL,

    Price DECIMAL(15, 2) NOT NULL,
    Sale TINYINT UNSIGNED DEFAULT 0,

    Country CHAR(16) NOT NULL,
    Category CHAR(16) NOT NULL,

    Avatar VARCHAR(200) NOT NULL,

    PRIMARY KEY (ID),

    FOREIGN KEY (Country) REFERENCES Countries(ID),
    FOREIGN KEY (Category) REFERENCES Categories(ID),

    CHECK (Sale <= 100)
);

CREATE TABLE IF NOT EXISTS ShowcaseCategories (
    ID CHAR(16),

    Category CHAR(16),

    PRIMARY KEY (ID),

    FOREIGN KEY (Category) REFERENCES Categories(ID)
);

CREATE TABLE IF NOT EXISTS HotTours (
    ID CHAR(16),

    Tour CHAR(16) NOT NULL,

    PRIMARY KEY (ID),

    FOREIGN KEY (Tour) REFERENCES Tours(ID)
);

CREATE TABLE IF NOT EXISTS Reviews (
    ID CHAR(16),

    Tour CHAR(16) NOT NULL,

    Author VARCHAR(20) NOT NULL,

    Content VARCHAR(1000) NOT NULL,
    Rating TINYINT UNSIGNED NOT NULL,

    PRIMARY KEY (ID),

    FOREIGN KEY (Tour) REFERENCES Tours(ID),
    FOREIGN KEY (Author) REFERENCES Members(Username),

    CHECK (1 >= Rating <= 5)
);

DELIMITER $$

CREATE EVENT IF NOT EXISTS ExpirationChecker
ON SCHEDULE
EVERY 1 SECOND
DO
    BEGIN
        DELETE FROM UnverifiedMembers WHERE Expiration < NOW();
        DELETE FROM ResetPasswordMembers WHERE Expiration < NOW();
    END; $$

DELIMITER ;