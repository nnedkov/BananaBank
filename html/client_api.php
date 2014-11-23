<?php

require_once 'aux_func.php';
require_once 'db.php';
require_once '../pdf/mpdf.php';


function reg_client() {

	print_debug_message('Checking if email & pass parameters are set...');
	if (empty($_POST['email']) or empty($_POST['pass']))
		return error('Email or password not specified');

	print_debug_message('Sanitizing input...');
	$email = sanitize_input($_POST['email']);
	$pass = sanitize_input($_POST['pass']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');

	print_debug_message('Checking if password content is valid...');
	if (!preg_match('/^[a-zA-Z0-9]*$/', $pass))
		return error('Invalid password (only letters and digits are allowed)');

	$res_arr = reg_client_db($email, $pass);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true', 'message' => null);

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

	$res_arr = login_client_db($email, $pass);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	session_start();
	session_regenerate_id();
	$_SESSION['email'] = $email;
	$_SESSION['is_employee'] = 'false';
	session_write_close();

	$res = array('status' => 'true', 'message' => null);

	echo json_encode($res);
}

function logout_client() {

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

	$res = array('status' => 'true', 'message' => null);

	echo json_encode($res);
}

function get_account_client() {

	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	$email = $_SESSION['email'];

	$res_arr = get_account_client_db($email);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null,
		     'email' => $email,
		     'balance' => $res_arr['balance'],
		     'account_number' => $res_arr['account_number']);

	echo json_encode($res);
}

function get_trans_client() {

	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	$email = $_SESSION['email'];

	$res_arr = get_trans_client_db($email);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null,
		     'trans' => $res_arr['trans_recs']);

	echo json_encode($res);
}

function get_trans_client_pdf() {

	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	$email = $_SESSION['email'];

	$res_arr = get_trans_client_db($email);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$html = output_trans_hist_html($email, $res_arr['trans_recs']);
	$mpdf = new mPDF();
	$mpdf->WriteHTML($html);
	$mpdf->Output('/var/www/downloads/'. $email .'.pdf', 'F');
}

function get_tancode_id() {

	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	$email = $_SESSION['email'];

	$res_arr = get_tancode_id_db($email);
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

	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	if (empty($_SESSION['tan_code_id']))
		return error('No tancode ID stored in the session');

	$email_src = $_SESSION['email'];
	$tancode_id = $_SESSION['tan_code_id'];

	print_debug_message('Checking if parameters are set...');
	if (empty($_POST['email_dest']))
		return error('Destination email not specified');
	if (empty($_POST['amount']))
		return error('Amount not specified');
	if (empty($_POST['tancode_value']))
		return error('TAN code value not specified');

	print_debug_message('Sanitizing input...');
	$email_dest = sanitize_input($_POST['email_dest']);
	$amount = sanitize_input($_POST['amount']);
	$tancode_value = sanitize_input($_POST['tancode_value']);

	if (strlen($tancode_value) != 15)
		return error('Tancode length should be 15!');

	$res_arr = set_trans_form_db($email_src, $email_dest, $amount, $tancode_id, $tancode_value);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true', 'message' => null);

	echo json_encode($res);
}

function set_trans_file() {

	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	if (empty($_SESSION['tan_code_id']))
		return error('No tancode ID stored in the session');

	$email_src = $_SESSION['email'];
	$tancode_id = $_SESSION['tan_code_id'];

	$res_arr = upload_file();
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$params = parse_file($filename);
	end($params);
	$value = current($params);

	if (count($value) != 1)
		return error('Uploaded file does not comply with rules! Last line should have only tan code');

	if (strlen($value[0]) != 15)
		return error('Tan code entered is not 15 characters!');

	$tancode_value = sanitize_input($value[0]);

	$res_arr = set_trans_file_db($email_src, $tancode_id, $tancode_value, $params);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true', 'message' => null);

	echo json_encode($res);
}

?>
