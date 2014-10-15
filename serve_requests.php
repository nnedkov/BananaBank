<?php

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$res = null;
	$action = $_GET['action'];
	switch ($action) {
		case "reg_client":	$res = reg_client();
					break;
  		case "login_client":	$res = login_client();
					break;
		case "show_history":	$res = show_history();
					break;
		case "login_emp":	$res = login_emp();
					break;
		case "list_clients":	$res = list_clients();
					break;
		case "approve_client":	$res = approve_client();
					break;
		case "reject_client":	$res = reject_client();
					break;
		case "list_payments":	$res = list_payments();
					break;
		case "approve_payment":	$res = approve_payment();
					break;
		case "reject_payment":	$res = reject_payment();
					break;
		default:		// send a status null
	}
	// header('Content-Type: application/json');
	echo json_encode($res);
}

function reg_client() {
	$res = array("status"=>"false");

	if (empty($_GET['email']) or empty($_GET['pass']))
		return $res;

	// sanitize input
	$email = test_input($_GET['email']);
	$pass = test_input($_GET['pass']);

	// check if email address is well-formed
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return $res;

	// check if pass contains only letters and digits
	if (!preg_match("/^[a-zA-Z0-9]*$/", $pass))
		return $res;

	try {
		$con = get_dbconn();
		$query = "select * from USERS where email='" . $email . "'";
		$result = mysqli_query($con, $query);
		$num_rows = mysqli_num_rows($result);
		if ($num_rows != 0) {
			echo "Already a record in the Db exists with this email!\n";
			return $res;
		}
		echo "We can add this motherfucker!\n";
		$query = "insert into USERS (email, password) values ('$email', '$pass')";
		$result = mysqli_query($con, $query);
		if (!$result)
			return $res;
		close_dbconn($con);
	} catch(Exception $e) {
  		echo "Message: " .$e->getMessage();
		return $res;
	}
	$res["status"] = "true";
	return $res;
}

function login_client() {
	$res = array("status"=>"false");
	
	if (empty($_GET['email']) or empty($_GET['pass']))
		return $res;

	// sanitize input
	$email = test_input($_GET['email']);
	$pass = test_input($_GET['pass']);

	// check if email address is well-formed
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return $res;

	// check if pass contains only letters and digits
	if (!preg_match("/^[a-zA-Z0-9]*$/", $pass))
		return $res;
	
	try {
		$con = get_dbconn();
		$query = "select * from USERS where email='" . $email . "' and password='" . $pass . "' and is_approved=1 and is_employee=0";
		$result = mysqli_query($con, $query);
		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0) {
			echo "Wrong email or password!\n";
			return $res;
		}
		echo "Correct credentials!\n";
		close_dbconn($con);
	} catch(Exception $e) {
  		echo "Message: " .$e->getMessage();
		return $res;
	}
	$res["status"] = "true";
	return $res;
}

function show_history() {
	$res = array("status"=>"false");

	if (empty($_GET['email']))
		return $res;

	// sanitize input
	$email = test_input($_GET['email']);

	// check if email address is well-formed
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return $res;

	try {
		$con = get_dbconn();
		$query = "select trans_id, email_src, email_dest, amount, date, is_approved
			  from TRANSACTIONS where email_src='" . $email . "' order by trans_id";
		$result = mysqli_query($con, $query);

		$trans_recs = null;
		while($rec = mysqli_fetch_array($result)) {
			$trans_recs[$rec["trans_id"]] = array($rec["email_dest"], $rec["amount"], $rec["date"], $rec["is_approved"]);
		}
		close_dbconn($con);
	} catch(Exception $e) {
  		echo "Message: " .$e->getMessage();
		return $res;
	}
	$res["status"] = "true";
	$res["trans"] = $trans_recs;
	return $res;
}

function login_emp() {
	$res = array("status"=>"false");
	
	if (empty($_GET['email']) or empty($_GET['pass']))
		return $res;

	// sanitize input
	$email = test_input($_GET['email']);
	$pass = test_input($_GET['pass']);

	// check if email address is well-formed
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return $res;

	// check if pass contains only letters and digits
	if (!preg_match("/^[a-zA-Z0-9]*$/", $pass))
		return $res;
	
	try {
		$con = get_dbconn();
		$query = "select * from USERS where email='" . $email . "' and password='" . $pass . "' and is_employee=1";
		$result = mysqli_query($con, $query);
		$num_rows = mysqli_num_rows($result);
		if ($num_rows == 0) {
			echo "Wrong email or password!\n";
			return $res;
		}
		echo "Correct credentials!\n";
		close_dbconn($con);
	} catch(Exception $e) {
  		echo "Message: " .$e->getMessage();
		return $res;
	}
	$res["status"] = "true";
	return $res;
}

function list_clients() {
	$res = array("status"=>"false");

	try {
		$con = get_dbconn();
		$query = "select email from USERS where is_approved=0";
		$result = mysqli_query($con, $query);

		$new_clients = array();
		while($rec = mysqli_fetch_array($result)) {
			array_push($new_clients, $rec["email"]);
		}
		close_dbconn($con);
	} catch(Exception $e) {
  		echo "Message: " .$e->getMessage();
		return $res;
	}
	$res["status"] = "true";
	$res["new_clients"] = $new_clients;
	return $res;
}

function approve_client() {
	$res = array("status"=>"false");
	
	if (empty($_GET['email']))
		return $res;

	// sanitize input
	$email = test_input($_GET['email']);

	// check if email address is well-formed
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return $res;

	try {
		$con = get_dbconn();
		$query = "update USERS set is_approved=1 where email='" . $email . "'";
		$result = mysqli_query($con, $query);
		if (!$result)
			return $res;
		close_dbconn($con);
	} catch(Exception $e) {
  		echo "Message: " .$e->getMessage();
		return $res;
	}
	// generate transactions codes for the client, store them in db and send them to his email
	$res["status"] = "true";
	return $res;
}

function reject_client() {
	$res = array("status"=>"false");
	
	if (empty($_GET['email']))
		return $res;

	// sanitize input
	$email = test_input($_GET['email']);

	// check if email address is well-formed
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return $res;

	try {
		$con = get_dbconn();
		$query = "delete from USERS where email='" . $email . "' and is_employee=0 and is_approved=0";
		$result = mysqli_query($con, $query);
		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0) {
			echo "No guy got deleted!\n";
			return $res;
		}
		close_dbconn($con);
	} catch(Exception $e) {
  		echo "Message: " .$e->getMessage();
		return $res;
	}
	// send email to client informing him that his registration was rejected
	$res["status"] = "true";
	return $res;
}

function list_payments() {
	$res = array("status"=>"false");

	try {
		$con = get_dbconn();
		$query = "select trans_id, email_src, email_dest, amount, date
			  from TRANSACTIONS where is_approved=0 order by trans_id";
		$result = mysqli_query($con, $query);

		$trans_recs = null;
		while($rec = mysqli_fetch_array($result)) {
			$trans_recs[$rec["trans_id"]] = array($rec["email_src"], $rec["email_dest"], $rec["amount"], $rec["date"]);
		}
		close_dbconn($con);
	} catch(Exception $e) {
  		echo "Message: " .$e->getMessage();
		return $res;
	}
	$res["status"] = "true";
	$res["trans"] = $trans_recs;
	return $res;
}

function approve_payment() {
	$res = array("status"=>"false");
	
	if (empty($_GET['trans_id']))
		return $res;

	// sanitize input
	$trans_id = test_input($_GET['trans_id']);

	// check if transaction id is well-formed
	if (!preg_match("/^[0-9]*$/", $trans_id))
		return $res;

	try {
		$con = get_dbconn();
		// perform the actual money transfer
		$query = "update TRANSACTIONS set is_approved=1 where trans_id='" . $trans_id . "'";
		$result = mysqli_query($con, $query);
		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0) {
			echo "No existing payment with such trans_id!\n";
			return $res;
		}
		close_dbconn($con);
	} catch(Exception $e) {
  		echo "Message: " .$e->getMessage();
		return $res;
	}
	$res["status"] = "true";
	return $res;
}

function reject_payment() {
	$res = array("status"=>"false");
	
	if (empty($_GET['trans_id']))
		return $res;

	// sanitize input
	$trans_id = test_input($_GET['trans_id']);

	// check if transaction id is well-formed
	if (!preg_match("/^[0-9]*$/", $trans_id))
		return $res;

	try {
		$con = get_dbconn();
		$query = "delete from TRANSACTIONS where trans_id='" . $trans_id . "' and is_approved=0";
		$result = mysqli_query($con, $query);
		$num_rows = mysqli_affected_rows($con);
		if ($num_rows == 0) {
			echo "No existing payment with such trans_id!\n";
			return $res;
		}
		close_dbconn($con);
	} catch(Exception $e) {
  		echo "Message: " .$e->getMessage();
		return $res;
	}
	// send email to client informing him that his transaction was rejected
	$res["status"] = "true";
	return $res;
}

function get_dbconn() {
	echo "Trying to connect!\n";
	$con = mysqli_connect("localhost","root","root","my_bank");

	// check connection
	if (mysqli_connect_errno()) {
		$con = null;
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	} else
		echo "We have connection!\n";
	return $con;
}

function close_dbconn($con) {
	if ($con != null)
		mysqli_close($con);
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>
