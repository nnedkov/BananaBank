<?php

require_once 'config.php';
require_once 'aux_func.php';


function get_dbconn() {

	global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;

	print_debug_message('Establishing new MySQL connection...');
	$con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

	if (mysqli_connect_errno()) {
		print_debug_message('Failed to connect to MySQL!' .  mysqli_connect_error());
		return null;
	}
	print_debug_message('Established new MySQL connection.');

	return $con;
}

function close_dbconn($con) {

	if ($con == null)
		return;
	print_debug_message('Closing MySQL connection...');
	mysqli_close($con);
	print_debug_message('Closed MySQL connection.');
}

?>
