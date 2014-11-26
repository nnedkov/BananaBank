<?php

require_once 'config.php';
require_once __DIR__ . '/../phppdf/mpdf.php';


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

function mail_tancodes($codes, $to, $account_num, $pass) {

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
                    Keep these codes safe, and don\'t show them to anyone! You will be asked to enter one each time you make a transaction.</p>

		    <table style="width:40%">
		    <tr>
		    <th>TAN Code ID</th>
		    <th>TAN Code</th>
		    </tr>';
	for ($i = 0 ; $i < count($codes) ; $i++) {
		$content = $content . '<tr>';
		$content = $content . '<td align="left">' . $codes[$i]['id'] . '</td>';
		$content = $content . '<td align="left">' . $codes[$i]['value'] . '</td>';
		$content = $content . '</tr>';
	}
	$content = $content . '</table>';

	$mpdf = new mPDF();
	$mpdf->SetProtection(array('copy', 'print'), $pass);
	$mpdf->WriteHTML($content);
	$filename = __DIR__ . '/../downloads/' . $account_num . '-' . rand(11, 99) . '.pdf';
	$mpdf->Output($filename, 'F');

	$subject = 'Your TAN codes';

	$body = 'Attached is the TAN codes for your bank account, please use the password we provided you to open it.';

	shell_exec('echo ' . $body . ' | mutt -s ' . $subject . ' -a ' . $filename . ' -- ' . $to);
	unlink($filename);

	return;
}

function mail_reject_account($to) {

	global $SYSTEM_EMAIL;

	$subject = 'Registration to Banana bank';

	$content = 'Dear Madame/Sir,\r\n we inform you that your registration to Banana bank was not approved.';

	$headers = 'From:' . $SYSTEM_EMAIL . '\r\n';
	$headers .= 'MIME-Version: 1.0\r\n';
	$headers .= 'Content-Transfer-Encoding: base64\r\n';
	$headers .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';

	$retval = mail($to, $subject, $content, $header);

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

function output_trans_hist_html($account_num, $trans_recs) {

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

		 <p> Transaction history of ' . $account_num . ' as of '. date('Y/m/d') . ' ' . date('h:i:s') . '<br></p>

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
		$html = $html . '<td align="center">' . $trans_rec[4] . '</td>';
		$is_approved = $trans_rec[5] == 0 ? 'not approved yet' : 'approved';
		$html = $html . '<td align="center">' . $is_approved . '</td>';
		$html = $html . '</tr>';
	}
	$html = $html . '</table>';

	return $html;
}

function upload_file() {

	if ($_FILES['uploadFile']['size'] > 500)
	    return array('status' => false, 'err_message' => 'Sorry, your file is too large.');

	if (!($_FILES['uploadFile']['type'] == 'text/plain'))
	    return array('status' => false, 'err_message' => 'Sorry, only text files are allowed.');

	$name = sanitize_input($_FILES['uploadFile']['name']);
	$target = __DIR__ . '/../uploads/' . $name;

	if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $target))
		print_debug_message('The file ' . basename($_FILES['uploadFile']['name']) . ' has been uploaded.');
	else
		return array('status' => false, 'err_message' => 'Whoops something went wrong while trying to upload the file!');

	return array('status' => true, 'filename' => $target);
}

function parse_file($filename) {

	print_debug_message('Parsing file ' . $res_arr['filename'] . '...');
        $handle = popen('../exe/set_trans_file ' . $filename, 'r');

	$params = array();
	while ($s = fgets($handle)) {
		if (ord($s) == 32) // check if line is empty
			break;
		$words = str_word_count($s, 1, '1234567890');
		if(count($words) != 3)
			return false;
		array_push($params, $words);
	}
	pclose($handle);

	return $params;
}

?>
