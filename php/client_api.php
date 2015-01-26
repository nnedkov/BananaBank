<?php

require_once 'aux_func.php';
require_once 'db.php';
require_once 'config.php';
require_once __DIR__ . '/../phppdf/mpdf.php';


function reg_client() {

	print_debug_message('Checking if parameters are set...');
	if (empty($_POST['email']) or empty($_POST['pass']))
		return error('Email or password not specified');
	if (empty($_POST['scs']))
		return error('Way of authenticating transactions not specified');

	print_debug_message('Sanitizing input...');
	$email = sanitize_input($_POST['email']);
	$pass = sanitize_input($_POST['pass']);
	$scs = sanitize_input($_POST['scs']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');
     	if (strlen($email) > 64)
		return error('Email length should be at most 64 characters');
	print_debug_message('Checking if password is strong enough...');
	if(!check_pass($pass))
		return error('Weak password. Make sure your is more than 6 characters and has at least one capital letter and one number');
	print_debug_message('Checking if way of authenticating transactions is valid...');
	if (!preg_match('/^[1-2]$/', $scs))
		return error('Invalid parameter (only 1 or 2 is allowed)');

	$res_arr = reg_client_db($email, $pass, $scs);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null,
		     'pdf_password' => $res_arr['pdf_password']);

	echo json_encode($res);
}

function login_client() {

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

	$res_arr = login_client_db($email, $pass);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	session_start();
	session_regenerate_id();
	$_SESSION['email'] = $email;
	$_SESSION['account_num'] = $res_arr['account_num'];
	$_SESSION['is_employee'] = 'false';
	$_SESSION['last_activity'] = time();
	session_write_close();

	$res = array('status' => 'true',
		     'message' => null);

	echo json_encode($res);
}

function logout_client() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	print_debug_message('Removing all session variables...');
	session_unset();

	print_debug_message('Destroying the session...');
	session_destroy();

	$res = array('status' => 'true',
		     'message' => null);

	echo json_encode($res);
}

function get_account_client() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);
	session_write_close();

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	$email = $_SESSION['email'];
	$account_num = $_SESSION['account_num'];

	$res_arr = get_account_client_db($account_num);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null,
		     'email' => $email,
		     'balance' => $res_arr['balance'],
		     'account_number' => $account_num);

	echo json_encode($res);
}

function get_trans_client() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);
	session_write_close();

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	$account_num = $_SESSION['account_num'];

	$res_arr = get_trans_client_db($account_num);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null,
		     'trans' => $res_arr['trans_recs']);

	echo json_encode($res);
}

function get_trans_client_pdf() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);
	session_write_close();

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	$account_num = $_SESSION['account_num'];

	$res_arr = get_trans_client_db($account_num);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$filename = __DIR__ . '/../.bank_downloads/' .  $res_arr['account_num']  . '.pdf';
	shell_exec('sudo /var/www/banana_bank/.bash/cleaner.sh ' . $filename);

	$html = output_trans_hist_html($account_num, $res_arr['trans_recs']);
	$mpdf = new mPDF();
	$mpdf->WriteHTML($html);
	$mpdf->Output($filename, 'F');
	$_SESSION['filename'] = $filename;
	$res = array('status' => 'true', 'url' => 'downloads.php');
	echo json_encode($res);
}

function get_tancode_id() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	$email = $_SESSION['email'];
	$account_num = $_SESSION['account_num'];

	$res_arr = get_tancode_id_db($email, $account_num);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$_SESSION['tan_code_id'] = $res_arr['tancode_id'];
	session_write_close();

	$res = array('status' => 'true',
		     'message' => null,
		     'tan_code_id' => $res_arr['tancode_id']);

	echo json_encode($res);
}

function set_trans_form() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	if (empty($_SESSION['tan_code_id']))
		return error('No tancode ID stored in the session');

	$account_num_src = $_SESSION['account_num'];
	$tancode_id = $_SESSION['tan_code_id'];
	$email = $_SESSION['email'];

	print_debug_message('Checking if parameters are set...');
	if (empty($_POST['account_num_dest']))
		return error('Destination account number not specified');
	if (empty($_POST['amount']))
		return error('Amount not specified');
	if (empty($_POST['tancode_value']))
		return error('TAN code value not specified');
	if (empty($_POST['description']))
		return error('Transaction description not specified');

	print_debug_message('Sanitizing input...');
	$account_num_dest = sanitize_input($_POST['account_num_dest']);
	$amount = sanitize_input($_POST['amount']);
	$tancode_value = sanitize_input($_POST['tancode_value']);
	$description = sanitize_input($_POST['description']);

	if (!preg_match('/^[0-9]*$/', $account_num_dest))
		return error('Invalid destination account number');

	if (!filter_var($amount, FILTER_VALIDATE_FLOAT) || floatval($amount) <= 0)
		return error('Invalid amount');
	$amount = floatval($amount);

	if (strlen($description) == 0)
		return error('Please provide description for the transaction');
	if (strlen($description) > 100)
		return error('Description length should be at most 100 characters');

	if ($tancode_id < 0){ // USE SCS instead
	#if (strlen($tancode_value) != 20)
	#	return error('SCS token length should be 20');
	} else {
	if (strlen($tancode_value) != 15)
		return error('Tancode length should be 15');
	}

	$res_arr = set_trans_form_db($email, $account_num_src, $account_num_dest, $amount, $tancode_id, $tancode_value, $description);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	unset($_SESSION['tan_code_id']);
	session_write_close();

	$res = array('status' => 'true',
		     'message' => null);

	echo json_encode($res);
}

function set_trans_file() {

	print_debug_message('Checking if parameters were set in the session during login...');
	session_start();
	$res_arr = is_valid_session();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	if (empty($_SESSION['tan_code_id']))
		return error('No tancode ID stored in the session');

	$email = $_SESSION['email'];
	$account_num_src = $_SESSION['account_num'];
	$tancode_id = $_SESSION['tan_code_id'];

	$res_arr = upload_file();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	//getting parsed file contents
	$params = parse_file($res_arr['filename']);
	if($params == false)
		return error('Uploaded file does not comply with rules');
	
	// checking last line (TAN or SCS token)
	end($params);
	$value = current($params);

	if (count($value) != 1)
		return error('Uploaded file does not comply with rules. Last line should have only TAN code or SCS token');

	// using TAN 
	if($tancode_id > 0){
		
		$tancode_value = sanitize_input($value[0]);
		if (strlen($tancode_value) != 15)
			return error('Check TAN code length');
	
		$res_arr = set_trans_file_db($email, $account_num_src, $tancode_id, $tancode_value, $params, 0);
		if ($res_arr['status'] == false)
			return error($res_arr['err_message']);
	} else {
		
		$scs_token = sanitize_input($value[0]);
		if (strlen($scs_token) != 20)
			return error('Check SCS Token length');
			
		//getting file contents from first elements and removing it from array
		$file_contents = array_shift($params);
		$file_contents = trim(preg_replace('/\s+/', '', $file_contents));
		$res_arr = set_trans_file_db($email, $account_num_src, $tancode_id, $scs_token, $params, $file_contents);
		if ($res_arr['status'] == false)
			return error($res_arr['err_message']);
	}
	
	unset($_SESSION['tan_code_id']);
	session_write_close();

	$res = array('status' => 'true',
		     'message' => null);

	echo json_encode($res);
}

?>
