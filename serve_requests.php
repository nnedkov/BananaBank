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
		default:
	}
	$data = array("status", $res);
	echo json_encode($data);
}

function reg_client() {
	if (empty($_GET['email']) or empty($_GET['pass']))
		return false;

	// sanitize input
	$email = test_input($_GET['email']);
	$pass = test_input($_GET['pass']);

	// check if email address is well-formed
     	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		return false;

	// check if pass contains only letters and digits
	if (!preg_match("/^[a-zA-Z0-9]*$/", $pass))
		return false;

	try {
		$con = get_dbconn();
		$query = "select * from USERS where email='" . $email . "'";
		$result = mysqli_query($con, $query);
		$num_rows = mysqli_num_rows($result);
		if ($num_rows != 0) {
			echo "Already a record in the Db exists with this email!\n";
			return false;
		}
		echo "We can add this motherfucker!\n";
		$query = "insert into USERS (email, password) values ('$email', '$pass')";
		$result = mysqli_query($con, $query);
		if (!$result)
			return false;
		close_dbconn($con);
	} catch(Exception $e) {
  		echo 'Message: ' .$e->getMessage();
		return false;
	}
	return true;
}

function login_client() {
}

function show_history() {
}

function login_emp() {
}

function list_clients() {
}

function approve_client() {
}

function reject_client() {
}

function list_payments() {
}

function approve_payment() {
}

function reject_payment() {
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
