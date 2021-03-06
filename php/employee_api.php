<?php

require_once 'aux_func.php';
require_once 'db.php';
require_once __DIR__ . '/../phppdf/mpdf.php';


function reg_emp() {

	print_debug_message('Checking if email & pass parameters are set...');
	if (empty($_POST['email']) or empty($_POST['pass']))
		return error('Email or password not specified');

	print_debug_message('Sanitizing input...');
	$email = sanitize_input($_POST['email']);
	$pass = sanitize_input($_POST['pass']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');
     	if (strlen($email) > 64)
		return error('Email length should be at most 64 characters');
	print_debug_message('Checking if password is strong enough...');
	if (!check_pass($pass))
		return error('Weak password. Make sure your is more than 6 characters and has at least one capital letter and one number');

	$res_arr = reg_emp_db($email, $pass);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null);

	echo json_encode($res);
}

function login_emp() {

	print_debug_message('Checking if email & pass parameters are set...');
	if (empty($_POST['email']) or empty($_POST['pass']))
		return error('Email or password not specified');

	print_debug_message('Sanitizing input...');
	$email = sanitize_input($_POST['email']);
	$pass = sanitize_input($_POST['pass']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');
     	if (strlen($email) > 64)
		return error('Email length should be at most 64 characters');

	$res_arr = login_emp_db($email, $pass);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	session_start();
	session_regenerate_id();
	$_SESSION['email'] = $email;
	$_SESSION['is_employee'] = 'true';
	$_SESSION['last_activity'] = time();
	session_write_close();

	$res = array('status' => 'true',
		     'message' => null);

	echo json_encode($res);
}

function logout_emp() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Removing all session variables...');
	session_unset();

	print_debug_message('Destroying the session...');
	session_destroy();

	$res = array('status' => 'true',
		     'message' => null);

	echo json_encode($res);
}

function get_clients() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);
	session_write_close();

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	$res_arr = get_clients_db();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null,
		     'clients' => $res_arr['clients']);

	echo json_encode($res);
}

function get_account_emp() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);
	session_write_close();

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Checking if email parameter is set...');
	if (empty($_POST['email']))
		return error('User not specified');

	print_debug_message('Sanitizing input...');
	$email = sanitize_input($_POST['email']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');
     	if (strlen($email) > 64)
		return error('Email length should be at most 64 characters');

	$res_arr = get_account_emp_db($email);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null,
		     'balance' => $res_arr['balance'],
		     'account_number' => $res_arr['account_number']);

	echo json_encode($res);
}

function get_trans_emp() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);
	session_write_close();

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Checking if email parameter is set...');
	if (empty($_POST['email']))
		return error('Email not specified');

	print_debug_message('Sanitizing input...');
	$email = sanitize_input($_POST['email']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');
     	if (strlen($email) > 64)
		return error('Email length should be at most 64 characters');

	$res_arr = get_trans_emp_db($email);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null,
		     'trans' => $res_arr['trans_recs']);

	echo json_encode($res);
}

function get_trans_emp_pdf() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Checking if email parameter is set...');
	if (empty($_POST['email']))
		return error('Email not specified');

	print_debug_message('Sanitizing input...');
	$email = sanitize_input($_POST['email']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');
     	if (strlen($email) > 64)
		return error('Email length should be at most 64 characters');

	$res_arr = get_trans_emp_db($email);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);
		
	$filename = '/var/www/banana_bank/.bank_downloads/' .  $account_num . '.pdf';
	
	$html = output_trans_hist_html($res_arr['account_num'], $res_arr['trans_recs']);
	$mpdf = new mPDF();
	$mpdf->WriteHTML($html);
	$mpdf->Output($filename, 'F');
	$_SESSION['filename'] = $filename;
	session_write_close();
	$res = array('status' => 'true', 'url' => 'download.php');
	echo json_encode($res);
}

function get_trans() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);
	session_write_close();

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	$res_arr = get_trans_db();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null,
		     'trans' => $res_arr['trans_recs']);

	echo json_encode($res);
}

function approve_trans() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);
	session_write_close();

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Checking if trans_id parameter is set...');
	if (empty($_POST['trans_id']))
		return error('Transaction id not specified');

	print_debug_message('Sanitizing input...');
	$trans_id = sanitize_input($_POST['trans_id']);

	print_debug_message('Checking if transaction id is valid...');
	if (!preg_match('/^[0-9]*$/', $trans_id))
		return error('Invalid transaction id');

	$res_arr = approve_trans_db($trans_id);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null);

	echo json_encode($res);
}

function reject_trans() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);
	session_write_close();

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Checking if trans_id parameter is set...');
	if (empty($_POST['trans_id']))
		return error('Transaction id not specified');

	print_debug_message('Sanitizing input...');
	$trans_id = sanitize_input($_POST['trans_id']);

	print_debug_message('Checking if transaction id is valid...');
	if (!preg_match('/^[0-9]*$/', $trans_id))
		return error('Invalid transaction id');

	$res_arr = reject_trans_db($trans_id);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	//mail_reject_trans($email);   # we don't have the email

	$res = array('status' => 'true',
		     'message' => null);

	echo json_encode($res);
}

function get_new_users() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);
	session_write_close();

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	$res_arr = get_new_users_db();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null,
		     'new_users' => $res_arr['new_users']);

	echo json_encode($res);
}

function approve_user() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);
	session_write_close();

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Checking if email parameter is set...');
	if (empty($_POST['email']))
		return error('Email not specified');
	print_debug_message('Checking if initial balance parameter is set...');
	if (empty($_POST['init_balance']))
		return error('Initial balance not specified');

	print_debug_message('Sanitizing input...');
	$email = sanitize_input($_POST['email']);
	$init_balance = sanitize_input($_POST['init_balance']);
	
	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');
     	if (strlen($email) > 64)
		return error('Email length should be at most 64 characters');

	print_debug_message('Checking if initial balance is a non-negative float...');
	if (!filter_var("".$init_balance, FILTER_VALIDATE_FLOAT) || floatval("".$init_balance) <= 0)
		return error('Initial balance should be a non-negative float');
	$init_balance = floatval($init_balance);

	$res_arr = approve_user_db($email, $init_balance);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null);

	echo json_encode($res);
}

function reject_user() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);
	session_write_close();

	if ($_SESSION['is_employee'] == 'false')
		return error('Unauthorized operation for client');

	print_debug_message('Checking if email parameter is set...');
	if (empty($_POST['email']))
		return error('Email not specified');

	print_debug_message('Sanitizing input...');
	$email = sanitize_input($_POST['email']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');
     	if (strlen($email) > 64)
		return error('Email length should be at most 64 characters');

	$res_arr = reject_user_db($email);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	mail_reject_account($email);

	$res = array('status' => 'true',
		     'message' => null);

	echo json_encode($res);
}

?>
