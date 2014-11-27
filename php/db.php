<?php

require_once 'dbconn.php';
require_once 'aux_func.php';


function reg_client_db($email, $pass, $pdf) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Checking if user with same email exists...');
		$email = mysql_real_escape_string($email);
		$query = 'select * from USERS
			  where email="' . $email . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows != 0)
			return array('status' => false,
				     'err_message' => 'Existing user with same email');

		print_debug_message('No registered user with same email exists. Inserting new user to db...');
		$hash = password_hash($pass, PASSWORD_DEFAULT);
		$pdf = mysql_real_escape_string($pdf);
		$query = 'insert into USERS (email, password, pdf)
			  values ("' . $email . '", "' . $hash . '", "' . $pdf . '")';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Unsuccesfully stored. Please try again');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true);
}

function login_client_db($email, $pass) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Checking if credentials were correct...');
		$email = mysql_real_escape_string($email);
		$query = 'select password, is_approved from USERS
			  where email="' . $email . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Wrong email or password');

		$rec = mysqli_fetch_array($result);
		$hash = $rec['password'];
		if (!password_verify($pass, $hash))
			return array('status' => false,
				     'err_message' => 'Wrong email or password');
		if ($rec['is_approved'] == 0)
			return array('status' => false,
				     'err_message' => 'Registration not approved yet');

		print_debug_message('Obtaining account number of user...');
		$query = 'select account_number from BALANCE
			  where email="' . $email . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Something went wrong');

		$row = mysqli_fetch_array($result);
		$account_num = $row['account_number'];

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true,
		     'account_num' => $account_num);
}

function get_account_client_db($account_num) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Obtaining account details of user...');
		$query = 'select balance from BALANCE
			  where account_number="' . $account_num . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => "User doesn't have an account");

		$row = mysqli_fetch_array($result);
		$balance = $row['balance'];

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true,
		     'balance' => $balance);
}

function get_trans_client_db($account_num) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Obtaining transaction records...');
		$query = 'select trans_id, account_num_dest, amount, description, date, is_approved from TRANSACTIONS
			  where account_num_src="' . $account_num . '"
			  order by trans_id';
		$result = mysqli_query($con, $query);

		$trans_recs = array();
		while ($rec = mysqli_fetch_array($result)) {
			$trans_rec = array($rec['trans_id'],
					   $rec['account_num_dest'],
					   $rec['amount'],
					   $rec['description'],
					   $rec['date'],
					   $rec['is_approved']);
			array_push($trans_recs, $trans_rec);
		}

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true,
		     'trans_recs' => $trans_recs);
}

function get_tancode_id_db($account_num) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Obtaining free tancode id of user...');
		$query = 'select tancode_id from TRANSACTION_CODES
			  where account_number="' . $account_num . '" and
			  is_used=0';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'No free tancodes available');

		$number = rand(0, $num_rows-1);
		if (!mysqli_data_seek($result, $number))
			return array('status' => false,
				     'err_message' => 'Something went wrong. Please try again');

		$row = mysqli_fetch_array($result);
		$tancode_id = $row['tancode_id'];

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true,
		     'tancode_id' => $tancode_id);
}

function set_trans_form_db($account_num_src, $account_num_dest, $amount, $tancode_id, $tancode_value, $description) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Checking if tancode is valid...');
		$tancode_value = mysql_real_escape_string($tancode_value);
		$query = 'select is_used from TRANSACTION_CODES where
			  account_number= "' . $account_num_src . '" and
			  tancode_id="' . $tancode_id . '" and
			  tancode="' . $tancode_value . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'You entered an invalid tancode');

		$row = mysqli_fetch_array($result);
		if ($row['is_used'] != 0)
			return array('status' => false,
				     'err_message' => 'You entered an already used tancode');

		$res_arr = transfer_money($account_num_src, $account_num_dest, $amount, $description, 0);
		if ($res_arr['status'] == false)
			return $res_arr;

		$query = 'update TRANSACTION_CODES set is_used=1
			  where account_number="' . $account_num_src . '" and
			  tancode_id="' . $tancode_id . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => "Code wasn't set to used");

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true);
}

function set_trans_file_db($account_num_src, $tancode_id, $tancode_value, $params) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Checking if tancode is valid...');
		$tancode_value = mysql_real_escape_string($tancode_value);
		$query = 'select is_used from TRANSACTION_CODES where
			  account_number="' . $account_num_src . '" and
			  tancode_id="' . $tancode_id . '" and
			  tancode="' . $tancode_value . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'You entered an invalid tancode');

		$row = mysqli_fetch_array($result);
		if ($row['is_used'] != 0)
			return array('status' => false,
				     'err_message' => 'You entered an already used tancode');

		for ($i = 0 ; $i < count($params)-1 ; $i++) {

			if (!filter_var($params[$i][1], FILTER_VALIDATE_FLOAT) || floatval($params[$i][1]) <= 0)
				return array('status' => false,
					     'err_message' => 'Invalid amount in some of the transactions');
			$params[$i][1] = floatval($params[$i][1]);
		}

		for ($i = 0 ; $i < count($params)-1 ; $i++) {

			$res_arr = transfer_money($account_num_src, $params[$i][0], $params[$i][1], $params[$i][2], 0);
			if ($res_arr['status'] == false)
				return $res_arr;
		}

		$query = 'update TRANSACTION_CODES set is_used=1
			  where account_number="' . $account_num_src . '" and
			  tancode_id="' . $tancode_id .'"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => "Code wasn't set to used");

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true);
}

function transfer_money($account_num_src, $account_num_dest, $amount, $description, $approval) {

	if ($amount <= 0)
		return array('status' => false,
			     'err_message' => 'Only amounts larger than zero can be sent');

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Checking if destination user exists...');
		$account_num_dest = mysql_real_escape_string($account_num_dest);
		$query = 'select * from BALANCE
			  where account_number="' . $account_num_dest . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Destination account is not registered or approved');

		print_debug_message('Checking if source user has sufficient balance...');
		$query = 'select balance from BALANCE
			  where account_number="' . $account_num_src . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Something went wrong. Please try again');

		$amount = mysql_real_escape_string($amount);
		$row = mysqli_fetch_array($result);
		if ($row['balance'] < $amount)
			return array('status' => false,
				     'err_message' => 'Your current balance is not sufficient to perform this transaction');

		mysqli_autocommit($con, false);
		if ($amount <= 10000 || $approval == 1) {
			$is_approved = 1;

			print_debug_message('Debiting ' . $amount . ' from ' . $account_num_src . '...');
			$query = 'update BALANCE set balance=balance-' . $amount . '
				  where account_number="' . $account_num_src . '"';
			$result = mysqli_query($con, $query);

			$num_rows = mysqli_affected_rows($con);
			if ($num_rows == 0)
				return array('status' => false,
					     'err_message' => 'Something went wrong. Please try again');

			print_debug_message('Crediting ' . $amount . ' to ' . $account_num_dest . '...');
			$query = 'update BALANCE set balance=balance+' . $amount . '
				  where account_number="' . $account_num_dest . '"';
			$result = mysqli_query($con, $query);

			$num_rows = mysqli_affected_rows($con);
			if ($num_rows == 0)
				return array('status' => false,
					     'err_message' => 'Something went wrong. Please try again');
		} else {
			print_debug_message('Transaction needs approval from an employee...');
			$is_approved = 0;
		}

		if ($approval == 0) {
			$description = mysql_real_escape_string($description);
			$query = 'insert into TRANSACTIONS (account_num_src, account_num_dest, amount, description, is_approved)
			          values ("' . $account_num_src . '", "'
				             . $account_num_dest . '", "'
				             . $amount . '", "'
				             . $description . '", "'
				             . $is_approved . '")';

			$result = mysqli_query($con, $query);
			$num_rows = mysqli_affected_rows($con);
			if ($num_rows == 0)
				return array('status' => false,
					     'err_message' => 'Something went wrong. Please try again');
		}

		mysqli_commit($con);
		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true);
}

function reg_emp_db($email, $pass) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Checking if user with same email exists...');
		$email = mysql_real_escape_string($email);
		$query = 'select * from USERS
			  where email="' . $email . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows != 0)
			return array('status' => false,
				     'err_message' => 'Existing user with same email');

		print_debug_message('No registered user with same email exists. Inserting new user to db...');
		$hash = password_hash($pass, PASSWORD_DEFAULT);
		$query = 'insert into USERS (email, password, is_employee)
			  values ("' . $email . '", "' . $hash . '", 1)';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Unsuccesfully stored. Please try again');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true);
}

function login_emp_db($email, $pass) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Checking if credentials were correct...');
		$email = mysql_real_escape_string($email);
		$query = 'select password, is_approved from USERS
			  where email="' . $email . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Wrong email or password');

		$rec = mysqli_fetch_array($result);
		$hash = $rec['password'];
		if (!password_verify($pass, $hash))
			return array('status' => false,
				     'err_message' => 'Wrong email or password');
		if ($rec['is_approved'] == 0)
			return array('status' => false,
				     'err_message' => 'Registration not approved yet');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true);
}

function get_clients_db() {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Obtaining list of all clients...');
		$query = 'select email from USERS
			  where is_employee=0
			  and is_approved=1';
		$result = mysqli_query($con, $query);

		$clients = array();
		while ($rec = mysqli_fetch_array($result))
			array_push($clients, $rec['email']);

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true,
		     'clients' => $clients);
}

function get_account_emp_db($email) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Obtaining user account details...');
		$email = mysql_real_escape_string($email);
		$query = 'select balance, account_number from BALANCE
			  where email="' . $email . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Something went wrong');

		$row = mysqli_fetch_array($result);
		$balance = $row['balance'];
		$account_number = $row['account_number'];

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true,
		     'balance' => $balance,
		     'account_number' => $account_number);
}

function get_trans_emp_db($email) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Obtaining user account number...');
		$email = mysql_real_escape_string($email);
		$query = 'select account_number from BALANCE
			  where email="' . $email . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Something went wrong. Please try again');

		$row = mysqli_fetch_array($result);
		$account_num = $row['account_number'];

		print_debug_message('Obtaining transaction records...');
		$account_num = mysql_real_escape_string($account_num);
		$query = 'select trans_id, account_num_src, account_num_dest, amount, description, date, is_approved from TRANSACTIONS
			  where account_num_src="' . $account_num . '"
			  order by trans_id';
		$result = mysqli_query($con, $query);

		$trans_recs = array();
		while ($rec = mysqli_fetch_array($result)) {
			$trans_rec = array($rec['trans_id'],
					   $rec['account_num_dest'],
					   $rec['amount'],
					   $rec['description'],
					   $rec['date'],
					   $rec['is_approved']);
			array_push($trans_recs, $trans_rec);
		}

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true,
		     'account_num' => $account_num,
		     'trans_recs' => $trans_recs);
}

function get_trans_db() {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Obtaining unapproved transaction records...');
		$query = 'select trans_id, account_num_src, account_num_dest, amount, description, date from TRANSACTIONS
			  where is_approved=0
			  and amount>=10000
			  order by trans_id';
		$result = mysqli_query($con, $query);

		$trans_recs = array();
		while ($rec = mysqli_fetch_array($result)) {
			$trans_rec = array($rec['trans_id'],
					   $rec['account_num_src'],
					   $rec['account_num_dest'],
					   $rec['amount'],
					   $rec['description'],
					   $rec['date']);
			array_push($trans_recs, $trans_rec);
		}

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true,
		     'trans_recs' => $trans_recs);
}

function approve_trans_db($trans_id) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Checking if transaction exists...');
		$trans_id = mysql_real_escape_string($trans_id);
		$query = 'select account_num_src, account_num_dest, amount, is_approved from TRANSACTIONS
			  where trans_id="' . $trans_id . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Non existing transaction with the specified id');

		$row = mysqli_fetch_array($result);
		if($row['is_approved'] == 1)
			return array('status' => false,
				     'err_message' => 'Transaction has been already approved');

		$res_arr = transfer_money($row['account_num_src'], $row['account_num_dest'], $row['amount'], '', 1);
		if ($res_arr['status'] == false)
			return $res_arr;

		$query = 'update TRANSACTIONS set is_approved=1
			  where trans_id="' . $trans_id . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Something went wrong');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true);
}

function reject_trans_db($trans_id) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Deleting transaction from db...');
		$trans_id = mysql_real_escape_string($trans_id);
		$query = 'delete from TRANSACTIONS
			  where trans_id="' . $trans_id . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Non existing transaction with the specified id');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true);
}

function get_new_users_db() {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Obtaining new users...');
		$query = 'select email, is_employee from USERS
			  where is_approved=0';
		$result = mysqli_query($con, $query);

		$new_users = array();
		while ($rec = mysqli_fetch_array($result)) {
			$user_type = $rec['is_employee'] == 1 ? 'employee' : 'client';
			$new_user = array($rec['email'], $user_type);
			array_push($new_users, $new_user);
		}

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true,
		     'new_users' => $new_users);
}

function approve_user_db($email, $init_balance) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Approving new user...');
		$email = mysql_real_escape_string($email);
		$query = 'update USERS set is_approved=1
			  where email="' . $email . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Non existing user with the specified email');

		print_debug_message('Obtaining info about user...');
		$query = 'select is_employee, pdf from USERS
			  where email="'. $email. '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Something went wrong');

		$row = mysqli_fetch_array($result);
		$is_employee = $row['is_employee'];
		$pdf = $row['pdf'];

		if ($is_employee == 0) {

			print_debug_message('Setting initial balance...');
			$init_balance = mysql_real_escape_string($init_balance);
			$query = 'insert into BALANCE (email, balance)
				  values ("' . $email . '", ' . $init_balance . ')';
			$result = mysqli_query($con, $query);

			$num_rows = mysqli_affected_rows($con);
			if ($num_rows == 0)
				return array('status' => false,
					     'err_message' => "Couldn't set initial balance. Please try again");

			print_debug_message('Obtaining account number of user...');
			$query = 'select LAST_INSERT_ID()';
			$result = mysqli_query($con, $query);

			$num_rows = mysqli_num_rows($result);
			if ($num_rows == 0)
				return array('status' => false,
					     'err_message' => 'Something went wrong');

			$row = mysqli_fetch_array($result);
			$account_num = $row[0];

			if ($pdf == 1) {

				$codes = array();
				for ($i = 0 ; $i < 100 ; $i++) {
					print_debug_message('Storing tancodes...');
					$codes[$i]['value'] = uniqid(chr(mt_rand(97, 122)).chr(mt_rand(97, 122)));
					$query = 'insert into TRANSACTION_CODES (account_number, tancode)
					          values ("' . $account_num . '", "' . $codes[$i]['value'] . '")';
					$result = mysqli_query($con, $query);

					$num_rows = mysqli_affected_rows($con);
					if ($num_rows == 0)
						return array('status' => false,
							     'err_message' => 'Whoops, something went wrong while adding tancodes');

					$query = 'select LAST_INSERT_ID()';
					$result = mysqli_query($con, $query);

					$num_rows = mysqli_num_rows($result);
					if ($num_rows == 0)
						return array('status' => false,
							     'err_message' => 'Something went wrong');

					$row = mysqli_fetch_array($result);
					$codes[$i]['id'] = $row[0];
				}

				$pass = 'test';  # TODO: make it dynamic
				mail_tancodes($codes, $email, $account_num, $pass);
			}
		}

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true);
}

function reject_user_db($email) {

	try {
		$con = get_dbconn();
		if ($con == null)
			return array('status' => false,
				     'err_message' => 'Failed to connect to database');

		print_debug_message('Deleting new user...');
		$email = mysql_real_escape_string($email);
		$query = 'delete from USERS
			  where email="' . $email . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return array('status' => false,
				     'err_message' => 'Non existing user with the specified email');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return array('status' => false,
			     'err_message' => 'Something went wrong. Please try again');
	}

	return array('status' => true);
}

?>
