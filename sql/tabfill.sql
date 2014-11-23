--   Filename: tabfill.sql
--   Secure Coding project
--   Oct 2014

INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('employee1@mybank.de', '12345', 1, 1);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('employee2@mybank.de', '12345', 1, 0);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('client1@mybank.de', '12345', 0, 1);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('client2@mybank.de', '12345', 0, 1);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('client3@mybank.de', '12345', 0, 1);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('client4@mybank.de', '12345', 0, 0);

INSERT INTO `TRANSACTIONS` (`account_num_src`, `account_num_dest`, `amount`, `description`, `is_approved`)
VALUES ('1', '2', 200, 'description1', 1);
INSERT INTO `TRANSACTIONS` (`account_num_src`, `account_num_dest`, `amount`, `description`, `is_approved`)
VALUES ('1', '3', 400, 'description2', 1);
INSERT INTO `TRANSACTIONS` (`account_num_src`, `account_num_dest`, `amount`, `description`, `is_approved`)
VALUES ('1', '2', 800, 'description3', 1);
INSERT INTO `TRANSACTIONS` (`account_num_src`, `account_num_dest`, `amount`, `description`, `is_approved`)
VALUES ('1', '3', 300, 'description4', 1);
INSERT INTO `TRANSACTIONS` (`account_num_src`, `account_num_dest`, `amount`, `description`, `is_approved`)
VALUES ('1', '3', 11000, 'description5', 0);

INSERT INTO `BALANCE` (`email`, `balance`) VALUES ('client1@mybank.de', 53000);
INSERT INTO `BALANCE` (`email`, `balance`) VALUES ('client2@mybank.de', 4000);
INSERT INTO `BALANCE` (`email`, `balance`) VALUES ('client3@mybank.de', 13000);
