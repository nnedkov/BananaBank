--   Filename: tabcreate.sql
--   Secure Coding project
--   Oct 2014

-- create indexes later e.g. INDEX `email-b` (`email`) ,

CREATE TABLE IF NOT EXISTS `USERS` (
  `email` VARCHAR( 64 ) NOT NULL ,
  `password` VARCHAR( 64 ) NOT NULL ,
  `is_employee` BOOLEAN NOT NULL DEFAULT 0 ,
  `is_approved` BOOLEAN NOT NULL DEFAULT 0 ,
  PRIMARY KEY  (`email`)
) ENGINE=MYISAM;


CREATE TABLE IF NOT EXISTS `TRANSACTION_CODES` (
  `email` VARCHAR( 64 ) NOT NULL ,
  `tancode_id` SMALLINT( 2 ) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tancode` VARCHAR( 15 ) NOT NULL ,
  `is_used` BOOLEAN NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`email`, `tancode_id`)
) ENGINE=MYISAM;


CREATE TABLE IF NOT EXISTS `TRANSACTIONS` (
  `trans_id` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email_src` VARCHAR( 64 ) NOT NULL ,
  `email_dest` VARCHAR( 64 ) NOT NULL ,
  `amount` MEDIUMINT( 8 ) UNSIGNED NOT NULL ,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `is_approved` BOOLEAN NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`trans_id`)
) ENGINE=MYISAM;


CREATE TABLE IF NOT EXISTS `BALANCE` (
  `email` VARCHAR( 64 ) NOT NULL ,
  `balance` FLOAT( 10 , 4 ) NOT NULL ,
  `account_number` INT (25) NOT NULL ,
  PRIMARY KEY (`email`)
) ENGINE=MYISAM;

