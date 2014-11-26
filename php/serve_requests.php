<?php

require_once 'config.php';
require_once 'aux_func.php';
require_once 'client_api.php';
require_once 'employee_api.php';


if ($_SERVER['REQUEST_METHOD'] != 'POST')
	return error('Accepting only POST requests');

if (!empty($_POST['action']))
	$action = $_POST['action'];
else
	$action = 'set_trans_file';

switch ($action) {
	// Client API
	case 'reg_client':	return reg_client();
	case 'login_client':	return login_client();
	case 'logout_client':	return logout_client();
	case 'get_account_client':	return get_account_client();
	case 'get_trans_client':	return get_trans_client();
	case 'get_trans_client_pdf':	return get_trans_client_pdf();
	case 'get_tancode_id':		return get_tancode_id();
	case 'set_trans_form':	return set_trans_form();
	case 'set_trans_file':	return set_trans_file();
	// Employee API
	case 'reg_emp': return reg_emp();
	case 'login_emp':	return login_emp();
	case 'logout_emp':	return logout_emp();
	case 'get_clients':	return get_clients();
	case 'get_account_emp':	return get_account_emp();
	case 'get_trans_emp':	return get_trans_emp();
	case 'get_trans_emp_pdf':	return get_trans_emp_pdf();
	case 'get_trans':	return get_trans();
	case 'approve_trans':	return approve_trans();
	case 'reject_trans':	return reject_trans();
	case 'get_new_users':	return get_new_users();
	case 'approve_user':	return approve_user();
	case 'reject_user':	return reject_user();
	default:		return error('Unknown action');
}

?>
