<?php

require_once 'aux_func.php';
require_once 'db.php';
require_once __DIR__ . '/../phpsec/auth/user.php';


function recover_pass() {

	print_debug_message('Checking if parameters are set...');
	if (empty($_POST['email']))
		return error('Email not specified');

	print_debug_message('Sanitizing input...');
	$email = sanitize_input($_POST['email']);

	print_debug_message('Checking if email format is valid...');
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');
     	if (strlen($email) > 64)
		return error('Email length should be at most 64 characters');

	$res_arr = recover_pass_db($email);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	mail_token($email, $res_arr['token']);

	$res = array('status' => 'true',
		     'message' => null);

	echo json_encode($res);
}

function change_pass() {

	print_debug_message('Checking if parameters are set...');
	if (empty($_POST['token']))
		return error('Token not specified');
	if (empty($_POST['new_pass']))
		return error('New password not specified');

	print_debug_message('Sanitizing input...');
	$token = sanitize_input($_POST['token']);
	$new_pass = sanitize_input($_POST['new_pass']);

	print_debug_message('Checking if token format is valid...');
     	if (strlen($token) != 15)
		return error('Token length should be 15 characters');
	print_debug_message('Checking if password is strong enough...');
	if (strlen($new_pass) < 6 || phpsec\BasicPasswordManagement.strength($new_pass) < 0.4)
		return error('Weak password. Make sure your password is stronger');

	$res_arr = change_pass_db($token, $new_pass);
	if ($res_arr['status'] == false)
		return error($res_arr['err_message']);

	$res = array('status' => 'true',
		     'message' => null);

	echo json_encode($res);
}

?>
