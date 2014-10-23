#############################
#   Filename: Makefile      #
#   Secure Coding project   #
#   Oct 2014                #
#############################

DB_USER = root
DB_PASS = root


create_db:
	mysql --user=$(DB_USER) --password=$(DB_PASS) -e "CREATE DATABASE my_bank DEFAULT CHARACTER SET utf8;"
	mysql --user=$(DB_USER) --password=$(DB_PASS) --database=my_bank -A < sql/tabcreate.sql

fill_db:
	mysql --user=$(DB_USER) --password=$(DB_PASS) --database=my_bank -A < sql/tabfill.sql

drop_db:
	mysql --user=$(DB_USER) --password=$(DB_PASS) -e "DROP DATABASE my_bank;"

