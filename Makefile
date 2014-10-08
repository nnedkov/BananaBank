#############################
#   Filename: Makefile      #
#   Secure Coding project   #
#   Oct 2014                #
#############################


create_db:
	mysql --user=root --password=root -e "CREATE DATABASE my_bank DEFAULT CHARACTER SET utf8;"
	mysql --user=root --password=root --database=my_bank -A < sql/tabcreate.sql

fill_db:
	mysql --user=root --password=root --database=my_bank -A < sql/tabfill.sql

drop_db:
	mysql --user=root --password=root -e "DROP DATABASE my_bank;"

