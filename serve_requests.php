<?php

// configuration variables
$DEBUG_MODE = true;
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = 'root';
$DB_NAME = 'my_bank';

if ($_SERVER['REQUEST_METHOD'] != 'POST')
	return error('Accepting only POST requests');

if (empty($_POST['action']))
	return error('Action not specified');

$action = $_POST['action'];

switch ($action) {
	// CLIENT API
	case 'reg_client':	return reg_client(); // done + tested
	case 'login_client':	return login_client(); // done + tested
	case 'logout_client':	return logout_client(); // done + tested
	case 'get_account_client':	return get_account_client(); // Elias
	case 'get_trans_client':	return get_trans_client(); // done + tested
	case 'get_trans_client_pdf':	return get_trans_client_pdf();
	case 'get_tancode_id':		return get_tancode_id(); // Elias
	case 'set_trans_form':	return set_trans_form(); // Elias
	case 'set_trans_file':	return set_trans_file(); // Elias
	// EMPLOYEE API
	case 'reg_emp': return reg_emp(); // done + tested
	case 'login_emp':	return login_emp(); // done + tested
	case 'logout_emp':	return logout_emp(); // done + tested
	case 'get_clients':	return get_clients(); // Elias
	case 'get_account_emp':	return get_account_emp(); // Elias
	case 'get_trans_emp':	return get_trans_emp(); // done + tested
	case 'get_trans_emp_pdf':	return get_trans_emp_pdf();
	case 'get_trans':	return get_trans(); // done + tested
	case 'approve_trans':	return approve_trans(); // done + tested (transfer actual money)
	case 'reject_trans':	return reject_trans(); // done + tested (send email to client)
	case 'get_new_users':	return get_new_users(); // done + tested
	case 'approve_user':	return approve_user(); // done + tested (send trans codes)
	case 'reject_user':	return reject_user(); // done + tested (send email to user)
	default:		return error('Unknown action specified');
}

function error($message) {
	$res['status'] = false;
	$res['message'] = $message;

	echo json_encode($res);
}

function reg_client() {
	print_debug_message('Checking if email & pass parameters are set...');
	if (empty($_POST['email']) or empty($_POST['pass']))
		return error('Email or password not specified');

	print_debug_message('Sanitizing input...');
	$email = test_input($_POST['email']);
	$pass = test_input($_POST['pass']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');

	print_debug_message('Checking if password content is valid...');
	if (!preg_match('/^[a-zA-Z0-9]*$/', $pass))
		return error('Invalid password (only letters and digits are allowed)');

	try {
		$con = get_dbconn();

		print_debug_message('Checking if user with same email exists...');
		$email = mysql_real_escape_string($email);
		$query = 'select * from USERS
			  where email="' . $email . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows != 0)
			return error('Existing user with same email');

		print_debug_message('No registered user with same email exists. Inserting new user to db...');
		$pass = mysql_real_escape_string($pass);
		$query = 'insert into USERS (email, password)
			  values ("' . $email . '", "' . $pass . '")';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return error('Unsuccesfully stored. Please try again');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}

	$res['status'] = true;
	$res['message'] = null;

	echo json_encode($res);
}

function login_client() {
	print_debug_message('Checking if email & pass parameters are set...');
	if (empty($_POST['email']) or empty($_POST['pass']))
		return error('Email or password not specified');

	print_debug_message('Sanitizing input...');
	$email = test_input($_POST['email']);
	$pass = test_input($_POST['pass']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');

	try {
		$con = get_dbconn();

		print_debug_message('Checking if credentials were correct...');
		$email = mysql_real_escape_string($email);
		$pass = mysql_real_escape_string($pass);
		$query = 'select * from USERS
			  where email="' . $email . '" and
			  password="' . $pass . '" and
			  is_employee=0';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return error('Wrong email or password');

		$rec = mysqli_fetch_array($result);
		if ($rec['is_approved'] == 0)
			return error('Registration not approved yet');

		print_debug_message('Credentials were correct');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}

	session_start();
	session_regenerate_id();
	$_SESSION['email'] = $email;
	$_SESSION['is_employee'] = 'false';
	session_write_close();

	$res['status'] = true;
	$res['message'] = null;

	echo json_encode($res);
}

function logout_client() {
	// print_debug_message('Checking if email parameter was set in the session...');
	//if (empty($_POST['email']))
	//	return error('Email not specified');

	//print_debug_message('Sanitizing input...');
	//$email = test_input($_POST['email']);

	//print_debug_message('Checking if email format is valid...');
     	//if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	//	return error('Invalid email format');

	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	print_debug_message('Removing all session variables...');
	session_unset();

	print_debug_message('Destroying the session...');
	session_destroy();

	$res['status'] = true;
	$res['message'] = null;

	echo json_encode($res);
}

function get_account_client() {
	// TODO: get user email from session
}

function get_trans_client() {
	//print_debug_message('Checking if email parameter is set...');
	//if (empty($_POST['email']))
	//	return error('Email not specified');

	//print_debug_message('Sanitizing input...');
	//$email = test_input($_POST['email']);

	//print_debug_message('Checking if email format is valid...');
     	//if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	//	return error('Invalid email format');

	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	$email = $_SESSION['email'];

	try {
		$con = get_dbconn();

		print_debug_message('Obtaining transaction records...');
		$email = mysql_real_escape_string($email);
		$query = 'select trans_id, email_src, email_dest, amount, date, is_approved from TRANSACTIONS
			  where email_src="' . $email . '" order by trans_id';
		$result = mysqli_query($con, $query);

		$trans_recs = array();
		while ($rec = mysqli_fetch_array($result)) {
			$trans_rec = array($rec['trans_id'], $rec['email_dest'], $rec['amount'], $rec['date'], $rec['is_approved']);
			array_push($trans_recs, $trans_rec);
		}

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}

	$res['status'] = true;
	$res['message'] = null;
	$res['trans'] = $trans_recs;

	echo json_encode($res);
}

function get_trans_client_pdf() {
	// TODO: get user email from session
}

function get_tancode_id() {
	// TODO: get user email from session
}

function set_trans_form() {
// TODO: get user email from session
// KEEP IN MIND: * a TAN code must only work one-time
// 		 * entering a used TAN code for another transaction must be encountered with an error message
}

function set_trans_file() {
// TODO: get user email from session
// KEEP IN MIND: * a TAN code must only work one-time
// 		 * entering a used TAN code for another transaction must be encountered with an error message
}

function reg_emp() {
	print_debug_message('Checking if email & pass parameters are set...');
	if (empty($_POST['email']) or empty($_POST['pass']))
		return error('Email or password not specified');

	print_debug_message('Sanitizing input...');
	$email = test_input($_POST['email']);
	$pass = test_input($_POST['pass']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');

	print_debug_message('Checking if password content is valid...');
	if (!preg_match('/^[a-zA-Z0-9]*$/', $pass))
		return error('Invalid password (only letters and digits are allowed)');

	try {
		$con = get_dbconn();

		print_debug_message('Checking if user with same email exists...');
		$email = mysql_real_escape_string($email);
		$query = 'select * from USERS
			  where email="' . $email . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows != 0)
			return error('Already used email');

		print_debug_message('No registered user with same email exists. Inserting new user to db...');
		$pass = mysql_real_escape_string($pass);
		$query = 'insert into USERS (email, password, is_employee)
			  values ("' . $email . '", "' . $pass . '", 1)';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return error('Unsuccesfully stored. Please try again');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}

	$res['status'] = true;
	$res['message'] = null;

	echo json_encode($res);
}

function login_emp() {
	print_debug_message('Checking if email & pass parameters are set...');
	if (empty($_POST['email']) or empty($_POST['pass']))
		return error('Email or password not specified');

	print_debug_message('Sanitizing input...');
	$email = test_input($_POST['email']);
	$pass = test_input($_POST['pass']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');

	try {
		$con = get_dbconn();

		print_debug_message('Checking if credentials were correct...');
		$email = mysql_real_escape_string($email);
		$pass = mysql_real_escape_string($pass);
		$query = 'select * from USERS
			  where email="' . $email . '" and
			  password="' . $pass . '" and
			  is_employee=1';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return error('Wrong email or password');

		$rec = mysqli_fetch_array($result);
		if ($rec['is_approved'] == 0)
			return error('Registration not approved yet');

		print_debug_message('Credentials were correct');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}

	session_start();
	session_regenerate_id();
	$_SESSION['email'] = $email;
	$_SESSION['is_employee'] = 'true';
	session_write_close();

	$res['status'] = true;
	$res['message'] = null;

	echo json_encode($res);
}

function logout_emp() {
	//print_debug_message('Checking if email parameter is set...');
	//if (empty($_POST['email']))
	//	return error('Email not specified');

	//print_debug_message('Sanitizing input...');
	//$email = test_input($_POST['email']);

	//print_debug_message('Checking if email format is valid...');
     	//if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	//	return error('Invalid email format');

	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Removing all session variables...');
	session_unset();

	print_debug_message('Destroying the session...');
	session_destroy();

	$res['status'] = true;
	$res['message'] = null;

	echo json_encode($res);
}

function get_clients() {}

function get_account_emp() {}

function get_trans_emp() {
	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Checking if email parameter is set...');
	if (empty($_POST['email']))
		return error('Email not specified');

	print_debug_message('Sanitizing input...');
	$email = test_input($_POST['email']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');

	try {
		$con = get_dbconn();

		print_debug_message('Obtaining transaction records...');
		$email = mysql_real_escape_string($email);
		$query = 'select trans_id, email_src, email_dest, amount, date, is_approved from TRANSACTIONS
			  where email_src="' . $email . '" order by trans_id';
		$result = mysqli_query($con, $query);

		$trans_recs = array();
		while ($rec = mysqli_fetch_array($result)) {
			$trans_rec = array($rec['trans_id'], $rec['email_dest'], $rec['amount'], $rec['date'], $rec['is_approved']);
			array_push($trans_recs, $trans_rec);
		}

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}

	$res['status'] = true;
	$res['message'] = null;
	$res['trans'] = $trans_recs;

	echo json_encode($res);
}

function get_trans_emp_pdf() {}

function get_trans() {
	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	try {
		$con = get_dbconn();

		print_debug_message('Obtaining unapproved transaction records...');
		$query = 'select trans_id, email_src, email_dest, amount, date from TRANSACTIONS
			  where is_approved=0
			  and amount>=10000
			  order by trans_id';
		$result = mysqli_query($con, $query);

		$trans_recs = array();
		while ($rec = mysqli_fetch_array($result)) {
			$trans_rec = array($rec['trans_id'], $rec['email_src'], $rec['email_dest'], $rec['amount'], $rec['date']);
			array_push($trans_recs, $trans_rec);
		}

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}

	$res['status'] = true;
	$res['message'] = null;
	$res['trans'] = $trans_recs;

	echo json_encode($res);
}

function approve_trans() {
	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Checking if trans_id parameter is set...');
	if (empty($_POST['trans_id']))
		return error('Transaction id not specified');

	print_debug_message('Sanitizing input...');
	$trans_id = test_input($_POST['trans_id']);

	print_debug_message('Checking if transaction id is valid...');
	if (!preg_match('/^[0-9]*$/', $trans_id))
		return error('Invalid transaction id');

	try {
		$con = get_dbconn();

		// TODO: check if there is enough money, if yes perform the actual money transfer

		$trans_id = mysql_real_escape_string($trans_id);
		$query = 'update TRANSACTIONS set is_approved=1
			  where trans_id="' . $trans_id . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return error('Non existing transaction with the specified id');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}

	$res['status'] = true;
	$res['message'] = null;

	echo json_encode($res);
}

function reject_trans() {
	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Checking if trans_id parameter is set...');
	if (empty($_POST['trans_id']))
		return error('Transaction id not specified');

	print_debug_message('Sanitizing input...');
	$trans_id = test_input($_POST['trans_id']);

	print_debug_message('Checking if transaction id is valid...');
	if (!preg_match('/^[0-9]*$/', $trans_id))
		return error('Invalid transaction id');

	try {
		$con = get_dbconn();

		$trans_id = mysql_real_escape_string($trans_id);
		$query = 'delete from TRANSACTIONS
			  where trans_id="' . $trans_id . '"
			  and is_approved=0';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return error('Non existing transaction with the specified id');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}
	// TODO: send email to client to inform him that his transaction was rejected

	$res['status'] = true;
	$res['message'] = null;

	echo json_encode($res);
}

function get_new_users() {
	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	try {
		$con = get_dbconn();

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
		return error('Something went wrong. Please try again');
	}

	$res['status'] = true;
	$res['message'] = null;
	$res['new_users'] = $new_users;

	echo json_encode($res);
}

function approve_user() {
	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Checking if email parameter is set...');
	if (empty($_POST['email']))
		return error('Email not specified');

	print_debug_message('Sanitizing input...');
	$email = test_input($_POST['email']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');

	// TODO: if user is a client: generate transaction codes, store them in db and send them to his email
	//       * 100 unique transaction codes (1 code = 15 printable characters)
	//	 * all clients must have different transaction codes and one code must only work one-time

	try {
		$con = get_dbconn();

		print_debug_message('Approving new user...');
		$email = mysql_real_escape_string($email);
		$query = 'update USERS set is_approved=1
			  where email="' . $email . '" and
			  is_approved=0';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return error('Non existing user with the specified email');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}

	$res['status'] = true;
	$res['message'] = null;

	echo json_encode($res);
}

function reject_user() {
	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Checking if email parameter is set...');
	if (empty($_POST['email']))
		return error('Email not specified');

	print_debug_message('Sanitizing input...');
	$email = test_input($_POST['email']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');

	try {
		$con = get_dbconn();

		print_debug_message('Rejecting new user...');
		$email = mysql_real_escape_string($email);
		$query = 'delete from USERS
			  where email="' . $email . '" and
			  is_approved=0';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0)
			return error('Non existing user with the specified email');

		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}

	// TODO: send email to user informing him that his registration was rejected

	$res['status'] = true;
	$res['message'] = null;

	echo json_encode($res);
}

function print_debug_message($message) {
	global $DEBUG_MODE;
	if ($DEBUG_MODE)
		echo $message . '<br>';
}

function test_input($input) {
	$input = trim($input);
	$input = stripslashes($input);
	$input = htmlspecialchars($input);

	return $input;
}

function close_dbconn($con) {
	if ($con == null)
		return;
	print_debug_message('Closing MySQL connection...');
	mysqli_close($con);
	print_debug_message('Closed MySQL connection.');
}

function get_dbconn() {
	global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;

	print_debug_message('Establishing new MySQL connection...');
	$con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

	if (mysqli_connect_errno()) {
		print_debug_message('Failed to connect to MySQL!' .  mysqli_connect_error());
		return error('Failed to connect to database');
	}
	print_debug_message('Established new MySQL connection.');

	return $con;
}

// header('Content-Type: application/json');

?>
