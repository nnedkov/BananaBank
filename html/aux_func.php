<?php

require_once 'config.php';


function error($message) {

	header('Content-Type: application/json');
	$res['status'] = 'false';
	$res['message'] = $message;

	echo json_encode($res);
}

function print_debug_message($message) {

	global $DEBUG_MODE;
	global $SILENT_MODE;
	if ($DEBUG_MODE and $SILENT_MODE)
		error_log($message . '\n', 3, '/var/tmp/my-errors.log');
	elseif ($DEBUG_MODE)
		echo $message . '<br>';
}

function sanitize_input($input) {

	$input = trim($input);
	$input = stripslashes($input);
	$input = htmlspecialchars($input);

	return $input;
}

function mail_tancodes($codes, $to) {

	$subject = 'Your TAN codes';

	$content = '<!DOCTYPE html>
		    <html>
		    <head>
		    <style>
		    table, th, td {
		        border: 1px solid black;
		    }
		    </style>
		    </head>
		    <body>

		    <h2 align="center"> Banana Bank </h2>

		    <p>We would like to welcome you to our family. The Banana Bank family!</p>
		    <p> At Banana Bank, we care about the safety of your bananas. That\'s why we have sent you TAN codes that will help us make sure that nobody can access your bananas except you!
                    Keep these TAN codes safe, and don\'t show them to anyone! You will be asked to enter one each time you make a transaction.</p>

		    <table style="width:40%">
		    <tr>
		    <th>TAN Code ID</th>
		    <th>TAN Code</th>
		    </tr>';
	for ($i = 0 ; $i < count($codes) ; $i++) {
		$content = $content . '<tr>';
		$content = $content . '<td align="left">' . $codes[$i]['ID'] . '</td>';
		$content = $content . '<td align="left">' . $codes[$i]['value'] . '</td>';
		$content = $content . '</tr>';
	}
	$content = $content . '</table>';
	$content = chunk_split(base64_encode($content));

	$headers = 'From:e.hazbon@gmail.com\r\n';
	$headers .= 'MIME-Version: 1.0\r\n';
	$headers .= 'Content-Transfer-Encoding: base64\r\n';
	$headers .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';

	$retval = mail($to, $subject, $content, $headers);

	return $retval;
}

function mail_reject_trans($to) {

	global $SYSTEM_EMAIL;

	$subject = 'Transaction in Banana bank';

	$content = 'Dear Madame/Sir,\r\n we inform you that your transaction in Banana bank was not approved.';

	$headers = 'From:' . $SYSTEM_EMAIL . '\r\n';
	$headers .= 'MIME-Version: 1.0\r\n';
	$headers .= 'Content-Transfer-Encoding: base64\r\n';
	$headers .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';

	$retval = mail($to, $subject, $content, $header);

	return $retval;
}

function mail_reject_account($to) {

	$subject = 'Registration to Banana bank';

	$content = 'Dear Madame/Sir,\r\n we inform you that your registration to Banana bank was not approved.';

	$headers = 'From:' $SYSTEM_EMAIL . '\r\n';
	$headers .= 'MIME-Version: 1.0\r\n';
	$headers .= 'Content-Transfer-Encoding: base64\r\n';
	$headers .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';

	$retval = mail($to, $subject, $content, $header);

	return $retval;
}

function output_trans_hist_html($email, $trans_recs) {

	$html = '<!DOCTYPE html>
		 <html>
		 <head>
		 <style>
		 table, th, td {
		     border: 1px solid black;
		 }
		 </style>
		 </head>
		 <body>

		 <img style="vertical-align: top" src="./images/BananaBankLogo2.jpg" width="80" />
		 <h2 align="center"> Banana Bank </h2>

		 <p> Transaction history of ' . $email . ' as of '. date('Y/m/d') . ' ' . date('h:i:s') . '<br></p>

		 <table style="width:100%">
		 <tr>
		 <th>ID</th>
		 <th>Destination</th>
		 <th>Amount</th>
		 <th>Date</th>
		 <th>Status</th>
		 </tr>';

	for ($i = 0 ; $i < count($trans_recs) ; $i++) {
		$trans_rec = $trans_recs[$i];
		$html = $html . '<tr>';
		$html = $html . '<td align="center">' . $trans_rec[0] . '</td>';
		$html = $html . '<td align="left">' . $trans_rec[1] . '</td>';
		$html = $html . '<td align="right">' . $trans_rec[2] . '</td>';
		$html = $html . '<td align="center">' . $trans_rec[3] . '</td>';
		$is_approved = $trans_rec[4] == 0 ? 'not approved yet' : 'approved';
		$html = $html . '<td align="center">' . $is_approved . '</td>';
		$html = $html . '</tr>';
	}
	$html = $html . '</table>';

	return $html;
}

?>
