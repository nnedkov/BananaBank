#############################
#   Filename: Makefile      #
#   Secure Coding project   #
#   Oct 2014                #
#############################

DB_USER = root
DB_PASS = alpha12


create_db:
	mysql --user=$(DB_USER) --password=$(DB_PASS) -e "CREATE DATABASE my_bank DEFAULT CHARACTER SET utf8;"
	mysql --user=$(DB_USER) --password=$(DB_PASS) --database=my_bank -A < ./tabcreate.sql

fill_db:
	mysql --user=$(DB_USER) --password=$(DB_PASS) --database=my_bank -A < ./tabfill.sql

drop_db:
	mysql --user=$(DB_USER) --password=$(DB_PASS) -e "DROP DATABASE my_bank;"

