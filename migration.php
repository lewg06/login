CREATE TABLE `login`.`users` ( `id` INT(10) NOT NULL AUTO_INCREMENT , `surname` VARCHAR(255) NOT NULL , `name` VARCHAR(255) NOT NULL , `second_name` VARCHAR(255) NOT NULL , `email` VARCHAR(255) NOT NULL , `verification` VARCHAR(255) NOT NULL , `data` TIMESTAMP NOT NULL , `deleted` TIMESTAMP NULL , PRIMARY KEY (`id`), UNIQUE `email` (`email`)) ENGINE = InnoDB COMMENT = 'Таблица с пользователями';

ALTER TABLE `users` ADD `login` VARCHAR(255) NOT NULL AFTER `id`, ADD INDEX (`login`);
ALTER TABLE `users` ADD `password` VARCHAR(10) NOT NULL AFTER `login`;

INSERT INTO `users` (`id`, `surname`, `name`, `second_name`, `email`, `verification`, `data`, `deleted`) VALUES ('1', 'admin', '', '', 'admin@mail.ru', '', CURRENT_TIMESTAMP, NULL);
UPDATE `users` SET `login` = 'admin', `deleted` = NULL WHERE `users`.`id` = 1;