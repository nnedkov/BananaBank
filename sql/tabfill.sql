--   Filename: tabfill.sql
--   Secure Coding project
--   Oct 2014

INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('employee1@mybank.de', '12345', 1, 1);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('employee2@mybank.de', '12345', 1, 0);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('client1@mybank.de', '12345', 0, 1);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('client2@mybank.de', '12345', 0, 1);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('client3@mybank.de', '12345', 0, 1);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('client4@mybank.de', '12345', 0, 0);

INSERT INTO `TRANSACTIONS` (`email_src`, `email_dest`, `amount`, `is_approved`) VALUES ('client1@mybank.de', 'client2@mybank.de', 200, 1);
INSERT INTO `TRANSACTIONS` (`email_src`, `email_dest`, `amount`, `is_approved`) VALUES ('client1@mybank.de', 'client3@mybank.de', 400, 1);
INSERT INTO `TRANSACTIONS` (`email_src`, `email_dest`, `amount`, `is_approved`) VALUES ('client1@mybank.de', 'client2@mybank.de', 800, 1);
INSERT INTO `TRANSACTIONS` (`email_src`, `email_dest`, `amount`, `is_approved`) VALUES ('client1@mybank.de', 'client3@mybank.de', 300, 1);
INSERT INTO `TRANSACTIONS` (`email_src`, `email_dest`, `amount`, `is_approved`) VALUES ('client1@mybank.de', 'client3@mybank.de', 11000, 0);

INSERT INTO `BALANCE` (`email`, `balance`, `account_number`) VALUES ('client1@mybank.de', 53000, 817618274);
INSERT INTO `BALANCE` (`email`, `balance`, `account_number`) VALUES ('client2@mybank.de', 4000, 216458122);
INSERT INTO `BALANCE` (`email`, `balance`, `account_number`) VALUES ('client3@mybank.de', 13000, 129847127);
