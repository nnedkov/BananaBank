<?php

require_once 'config.php';
require_once 'aux_func.php';
require_once 'common_api.php';
require_once 'client_api.php';
require_once 'employee_api.php';


if ($_SERVER['REQUEST_METHOD'] != 'POST')
	return error('Accepting only POST requests');

if (!empty($_POST['action']))
	$action = $_POST['action'];
else
	$action = 'set_trans_file';

switch ($action) {
	// Common API
	case 'recover_pass':	return recover_pass();
	case 'change_pass':	return change_pass();
	// Client API
	case 'reg_client':	return reg_client(); # DONE
	case 'login_client':	return login_client(); # DONE
	case 'logout_client':	return logout_client(); # DONE
	case 'download_scs_exe':	return download_scs_exe(); 
	case 'get_account_client':	return get_account_client(); # DONE
	case 'get_trans_client':	return get_trans_client();  # DONE
	case 'get_trans_client_pdf':	return get_trans_client_pdf();
	case 'get_tancode_id':		return get_tancode_id(); # DONE
	case 'set_trans_form':	return set_trans_form(); # DONE
	case 'set_trans_file':	return set_trans_file();
	// Employee API
	case 'reg_emp': return reg_emp();
	case 'login_emp':	return login_emp(); # DONE
	case 'logout_emp':	return logout_emp(); # DONE
	case 'get_clients':	return get_clients(); # DONE
	case 'get_account_emp':	return get_account_emp(); # DONE
	case 'get_trans_emp':	return get_trans_emp(); # DONE
	case 'get_trans_emp_pdf':	return get_trans_emp_pdf();
	case 'get_trans':	return get_trans(); # DONE
	case 'approve_trans':	return approve_trans(); # DONE
	case 'reject_trans':	return reject_trans(); # DONE
	case 'get_new_users':	return get_new_users(); # DONE
	case 'approve_user':	return approve_user(); # DONE
	case 'reject_user':	return reject_user(); # DONE
	default:		return error('Unknown action');
}

?>
