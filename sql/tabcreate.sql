--   Filename: tabcreate.sql
--   Secure Coding project
--   Oct 2014


CREATE TABLE IF NOT EXISTS `USERS` (
  `email` VARCHAR ( 64 ) NOT NULL,
  `password` VARCHAR ( 255 ) NOT NULL,
  `is_employee` BOOLEAN NOT NULL DEFAULT 0,
  `is_approved` BOOLEAN NOT NULL DEFAULT 0,
  `pdf` SMALLINT ( 1 ) NOT NULL DEFAULT 2,
  PRIMARY KEY (`email`)
) ENGINE=MYISAM;


CREATE TABLE IF NOT EXISTS `BALANCE` (
  `account_number` INT ( 25 ) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR ( 64 ) NOT NULL,
  `balance` FLOAT ( 10 , 4 ) NOT NULL,
  PRIMARY KEY (`account_number`),
  FOREIGN KEY (`email`) REFERENCES USERS(`email`)
) ENGINE=MYISAM;


CREATE TABLE IF NOT EXISTS `TRANSACTION_CODES` (
  `account_number` INT ( 25 ) NOT NULL,
  `tancode_id` SMALLINT ( 2 ) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tancode` VARCHAR ( 15 ) NOT NULL,
  `is_used` BOOLEAN NOT NULL DEFAULT 0,
  PRIMARY KEY (`account_number`, `tancode_id`),
  FOREIGN KEY (`account_number`) REFERENCES BALANCE(`account_number`)
) ENGINE=MYISAM;


CREATE TABLE IF NOT EXISTS `TRANSACTIONS` (
  `trans_id` MEDIUMINT ( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_num_src` INT ( 25 ) NOT NULL,
  `account_num_dest` INT ( 25 ) NOT NULL,
  `amount` MEDIUMINT ( 8 ) UNSIGNED NOT NULL,
  `description` VARCHAR ( 120 ),
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_approved` BOOLEAN NOT NULL DEFAULT 0,
  PRIMARY KEY (`trans_id`),
  FOREIGN KEY (`account_num_src`) REFERENCES BALANCE(`account_number`),
  FOREIGN KEY (`account_num_dest`) REFERENCES BALANCE(`account_number`)
) ENGINE=MYISAM;

