<?php

require_once 'config.php';
require_once 'aux_func.php';
require_once 'dbconn.php';
require_once 'db.php';
require_once 'client_api.php';
require_once 'employee_api.php';
require_once '../pdf/mpdf.php';


if ($_SERVER['REQUEST_METHOD'] != 'POST')
	return error('Accepting only POST requests');

if (empty($_POST['action']))
	return set_trans_file();

$action = $_POST['action'];

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

function transfer_money($src, $dst, $amount, $approval) {
	$email_src = sanitize_input($src);
	$email_dest = sanitize_input($dst);
	$amount= sanitize_input($amount);
	
	print_debug_message('Checking if source email format is valid...');
     	if (!filter_var($email_src, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');
	print_debug_message('Checking if destination email format is valid...');
     	if (!filter_var($email_dest, FILTER_VALIDATE_EMAIL))
		return error('Invalid email format');

	try {
		$con = get_dbconn();

		print_debug_message('preparing to execute transaction...');
		$email_src = mysql_real_escape_string($email_src);
		$email_dest = mysql_real_escape_string($email_dest);
		$amount = mysql_real_escape_string($amount);
		
		$query = 'select * from USERS
			 where email="' . $email_dest . '"
			 and is_approved=1 and is_employee = 0';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return error('Destination is not registered or approved');
		
		$query = 'select * from USERS
			 where email="' . $email_src . '"
			 and is_approved=1';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return error('Source is not registered or approved');


		$query = 'select balance from BALANCE
			  where email="' . $email_src . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return error('Somthing went wrong!');
		
		$row = mysqli_fetch_array($result);

		$balance = $row['balance'];
		if($balance < $amount)
			return error('Your current balanace does not allow you to do this transaction!');
		
		print_debug_message('Executing Transaction...');
		if($amount <= 10000 || $approval == 1){
			$is_approved = 1;
	
			print_debug_message('Debiting ' .$amount. ' from Source...');		
			$query = 'update BALANCE set balance= balance - ' .$amount. '
				  where email="' . $email_src . '"';
			$result = mysqli_query($con, $query);

			$num_rows = mysqli_affected_rows($con);
			if ($num_rows == 0)
				return error('Whoops, Something went wrong. Please try again');
		
			print_debug_message('Crediting ' .$amount. ' to Destination...');
		
			$query = 'update BALANCE set balance= balance + ' .$amount. '
				  where email="' . $email_dest . '"';
			$result = mysqli_query($con, $query);
			
			$num_rows = mysqli_affected_rows($con);
			if ($num_rows == 0){
				$query = 'update BALANCE set balance=balance + ' .$amount. '
				  where email="' . $email_src . '"';
				$result = mysqli_query($con, $query);	
			return error('Whoops, Something went wrong. Please try again');
			}
		}else{
			$is_approved = 0;
		}	print_debug_message('Transaction needs approval of Employee...');				

		$query = 'insert into TRANSACTIONS (email_src, email_dest,
			amount, is_approved)
			values ("' . $email_src . '", "' . $email_dest . '", "' . $amount . '",
			 "' . $is_approved . '")';

		$result = mysqli_query($con, $query);
		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0){
			if($is_approved == 1){
				$query = 'update BALANCE set balance=balance + ' .$amount. '
				  where email="' . $email_src . '"';
				$result = mysqli_query($con, $query);	
				$query = 'update BALANCE set balance=balance - ' .$amount. '
				  where email="' . $email_dest . '"';
				$result = mysqli_query($con, $query);
			}
			return error('Whoops, Something went wrong. Please try again');
		}

		close_dbconn($con);
		return true;

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}
}

function set_trans_form() {
	print_debug_message('Checking if parameters were set during login in the session...');
	session_start();
	if (empty($_SESSION['email']) or empty($_SESSION['is_employee']))
		return error('Invalid session');

	if ($_SESSION['is_employee'] == 'true')
		return error('Invalid operation for employee');

	$email_src = $_SESSION['email'];
	
	if (empty($_POST['email_dest']))
		return error('Destination email not specified');
	if (empty($_POST['amount']))
		return error('amount not specified');
	if (empty($_POST['tancode_value']))
		return error('TAN code value not specified');
	if (empty($_SESSION['tan_code_id']))
		return error('No tancode ID stored in the session');
	$tancode_id = $_SESSION['tan_code_id'];

	print_debug_message('Sanitizing input...');	
	$tancode_value = $_POST['tancode_value'];
	if(strlen($tancode_value) != 15)
		return error('Tancode length should be 15!');
	
	
	try {
		$con = get_dbconn();

		$tancode_id = mysql_real_escape_string($tancode_id);		
		$query = 'select Is_used from TRANSACTION_CODES where 
			 email= "' . $email_src . '" and
			 trans_code_id= "' . $tancode_id . '" and 
			 trans_code = "' . $tancode_value . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return error('You entered an invalid Tancode!');
		$row = mysqli_fetch_array($result);					
		if($row['Is_used'] != 0)
			return error('You entered an already used tancode!');		
		
		$status = transfer_money($email_src,$_POST['email_dest'],$_POST['amount'],0);
		if($status == true){
			
			$query = 'update TRANSACTION_CODES set Is_used =1
				where email = "'. $email_src . '" and trans_code_id = "'. $tancode_id .'"';
			
			$result = mysqli_query($con, $query);
			$num_rows = mysqli_affected_rows($con);
			if ($num_rows == 0){
				print_debug_message("Code wasn't set to used!");  //TODO:make sure it does!
			}
		} else
			return;
		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}

	$res['status'] = "true";
	$res['message'] = null;

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
	$filename = upload_file();
	if($filename == FALSE)
		return;
	print_debug_message("parsing file " .$filename);
	parse_file($filename,$email_src,$tancode_id);
}

function parse_file($filename, $email_src, $tancode_id) {

        $handle = popen("./main ".$filename, "r");
				

	$params = array();
	while($s = fgets($handle)) {
		if(ord($s) == 32) //check if empty line
			break;
		$words = str_word_count($s,1,'1234567890!#$%&*+-/@=?^_`{|}~.');		
		array_push($params, $words);				
	}	
	end($params); 
	$key = key($params);
	$value = current($params);
	if(count($value) != 1)
		return error('Uploaded file does not comply with rules! Last line should have only tan code');
	if(strlen($value[0]) != 15)
		return error('Tan code entered is not 15 characters!');
	
	$tancode_value = sanitize_input($value[0]);
	
	try {
		$con = get_dbconn();

		$tancode_id = mysql_real_escape_string($tancode_id);
		$tancode_value = mysql_real_escape_string($tancode_value);		
		
		$query = 'select Is_used from TRANSACTION_CODES where 
			 email= "' . $email_src . '" and
			 trans_code_id= "' . $tancode_id . '" and 
			 trans_code = "' . $tancode_value . '"';
		$result = mysqli_query($con, $query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0)
			return error('You entered an invalid Tancode!');
		$row = mysqli_fetch_array($result);					
		if($row['Is_used'] != 0)
			return error('You entered an already used tancode!');		
		
		for($i = 0; $i < count($params)-1;$i++){
			$status = transfer_money($email_src,$params[$i][0],$params[$i][1],0);			
		}


		if($status == true){
			
			$query = 'update TRANSACTION_CODES set Is_used =1
				where email = "'. $email_src . '" and trans_code_id = "'. $tancode_id .'"';
			
			$result = mysqli_query($con, $query);
			$num_rows = mysqli_affected_rows($con);
			if ($num_rows == 0){
				print_debug_message("Code wasn't set to used!");  //TODO:make sure it does!
			}
		} else
			return;
		close_dbconn($con);

	} catch (Exception $e) {
		print_debug_message('Exception occured: ' . $e->getMessage());
		return error('Something went wrong. Please try again');
	}

	$res['status'] = "true";
	$res['message'] = null;

	echo json_encode($res);
}

function upload_file() {

	$target = "/var/www/uploads/{$_FILES['uploadFile']['name']}";
	$upload_ready=1;
	
	// Check file size
	if ($_FILES["uploadFile"]["size"] > 1000) {
	    echo "Sorry, your file is too large.";
	    $upload_ready = 0;
	}
	
	if (!($_FILES["uploadFile"]["type"] == "text/plain")) {
	    echo "Sorry, only text files are allowed.";
	    $upload_ready = 0;
	}
	
	if ($upload_ready == 0) {
	    echo "Your file was not uploaded.";
	    return FALSE;
	} else { 
	    if (move_uploaded_file($_FILES['uploadFile']['tmp_name'],$target)) {
	        print_debug_message("The file ". basename( $_FILES["uploadFile"]["name"]). " has been uploaded.");
	    } else {
	        return error("Whoops something went wrong while trying to upload the file!");
	    }
	}
	return "/var/www/uploads/{$_FILES['uploadFile']['name']}";
}

?>
