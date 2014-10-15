--   Filename: tabfill.sql
--   Secure Coding project
--   Oct 2014

INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('employee1@mybank.de', '1234', 1, 1);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('employee2@mybank.de', '2345', 1, 1);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('employee3@mybank.de', '3456', 1, 1);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('employee4@mybank.de', '4567', 1, 1);
INSERT INTO `USERS` (`email`, `password`, `is_employee`, `is_approved`) VALUES ('employee5@mybank.de', '5678', 1, 1);

INSERT INTO `TRANSACTIONS` (`email_src`, `email_dest`, `amount`, `is_approved`) VALUES ('employee1@mybank.de', 'employee2@mybank.de', 200, 0);
INSERT INTO `TRANSACTIONS` (`email_src`, `email_dest`, `amount`, `is_approved`) VALUES ('employee3@mybank.de', 'employee4@mybank.de', 200, 0);
INSERT INTO `TRANSACTIONS` (`email_src`, `email_dest`, `amount`, `is_approved`) VALUES ('employee2@mybank.de', 'employee4@mybank.de', 300, 1);
