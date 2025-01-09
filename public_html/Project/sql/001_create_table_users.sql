CREATE TABLE IF NOT EXISTS `Users` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(60) NOT NULL,
    `username` VARCHAR(30) NOT NULL UNIQUE DEFAULT (substring_index(email, '@', 1)),
    `points` INT NOT NULL DEFAULT(0),
    `is_public` tinyint(1) DEFAULT 0,
    `clauster_theme` INT NOT NULL DEFAULT(0),
    `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE (`email`)
)