CREATE DATABASE `counter`;

CREATE TABLE `counter`.`visitors` (
    `domain` VARCHAR (255) CHARSET utf8 COLLATE utf8_general_ci NOT NULL,
    `date` DATE NOT NULL,
    `count` INT UNSIGNED,
    `unique_count` INT UNSIGNED,
    PRIMARY KEY (`domain`, `date`)
) ENGINE = INNODB CHARSET = utf8 COLLATE = utf8_general_ci;