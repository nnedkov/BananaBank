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

function is_valid_session() {

	if (empty($_SESSION['email']) or
	    empty($_SESSION['is_employee']) or
	    empty($_SESSION['last_activity']))
		return array('status' => false,
			     'err_message' => 'Session is invalid');

	global $SESSION_DURATION;

	if (time() - $_SESSION['last_activity'] > $SESSION_DURATION) {
		session_unset();
		session_destroy();
		return array('status' => false,
			     'err_message' => 'Session has expired');
	}
	$_SESSION['last_activity'] = time();

	return array('status' => true);
}

function sanitize_input($input) {

	$input = trim($input);
	$input = stripslashes($input);
	$input = htmlspecialchars($input);

	return $input;
}

function mail_tancodes($email, $codes, $account_num, $pdf_password) {

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
	$mpdf->SetProtection(array('copy', 'print'), $pdf_password);
	$mpdf->WriteHTML($content);
	$filename = '/var/www/banana_bank/.bank_downloads/' . $account_num . '-' . rand(11, 99) . '.pdf';
	$mpdf->Output($filename, 'F');

	$subject = 'Your TAN codes';

	$body = 'Attached are the TAN codes for your bank account, please use the password we provided you to open it.';
	sleep(0.5);
	send_attachment($email,$subject,$body,$filename);
	sleep(0.5);
	
	unlink($filename);

	return;
}

function mail_scs($email, $scs_password, $account_num, $pdf_password, $scs_string) {
	
	$content = $content . $scs_password;
	$mpdf = new mPDF();
	$mpdf->SetProtection(array('copy', 'print'), $pdf_password);
	$mpdf->WriteHTML($content);
	$filename1 = '/var/www/banana_bank/.bank_downloads/' . $account_num . '-' . rand(11, 99) . '.pdf';
	$mpdf->Output($filename1, 'F');

	$subject = 'Your SCS PIN';

	$body = 'Attached is the SCS PIN for your bank account, please use the password we provided you to open the file.';

	shell_exec('sed \'/JTextField tanField/ a\   private String secret = "' . $scs_string . '";\' ../.java/Original.java > ../.java/src/BananaSCS.java');
	shell_exec('cd /var/www/banana_bank/.java/ && ant && cd ../.exe/ && ant -f BuildSCS.xml');
	shell_exec('mv ../.exe/SCS.jar ../.exe/SCS' . $account_num . '.jar');
	shell_exec('chmod +x ../.exe/SCS' . $account_num . '.jar');
	
	$filename2 =  '/var/www/banana_bank/.exe/SCS' . $account_num . '.jar';
	
	send_attachment($email,$subject,$body,$filename1);
	send_attachment($email,$subject,$body,$filename2);
	
	//removing temp files
	unlink($filename1);
	unlink($filename2);
	unlink('/var/www/banana_bank/.java/src/BananaSCS.java');	
	return;
}

function mail_reject_account($to) {

	global $SYSTEM_EMAIL;

	$subject = 'Registration to Banana bank';

	$content = 'Dear Madame/Sir, we regret to inform you that your registration to Banana bank was not approved.';

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

	$content = 'Dear Madame/Sir, we regret to inform you that your transaction in Banana bank was not approved.';

	$headers = 'From:' . $SYSTEM_EMAIL . '\r\n';
	$headers .= 'MIME-Version: 1.0\r\n';
	$headers .= 'Content-Transfer-Encoding: base64\r\n';
	$headers .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';

	$retval = mail($to, $subject, $content, $header);

	return $retval;
}

function mail_token($to, $token) {

	global $SYSTEM_EMAIL;

	$subject = 'Password recovery in Banana bank';

	$content = 'Dear Madame/Sir, click on the url below in order to change your password:  ';
	$content .= 'http://localhost/banana_bank/html/changePass.html?token=' . $token;

	$headers = 'From:' . $SYSTEM_EMAIL . '\r\n';
	$headers .= 'MIME-Version: 1.0\r\n';
	$headers .= 'Content-Transfer-Encoding: base64\r\n';
	$headers .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';

	$retval = mail($to, $subject, $content, $headers);

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

		 <img style="vertical-align: top" src="/var/www/banana_bank/html/images/BananaBankLogo2.jpg" width="80" />
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
	
	
	if ($_FILES['uploadFile']['size'] > 2000)
	    return array('status' => false,
			 'err_message' => 'Sorry, your file is too large');

	if (!($_FILES['uploadFile']['type'] == 'text/plain'))
	    return array('status' => false,
			 'err_message' => 'Sorry, only text files are allowed');

	$target = '/var/www/banana_bank/.bank_uploads/transaction_' . substr(basename($_FILES['uploadFile']['name']), 0, 3) . '.txt';

	if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $target))
		print_debug_message('The file ' . basename($_FILES['uploadFile']['name']) . ' has been uploaded.');
	else
		return array('status' => false,
			     'err_message' => 'Whoops, something went wrong while trying to upload the file');

	return array('status' => true,
		     'filename' => $target);
}

function parse_file($filename) {

	print_debug_message('Parsing file ' . $filename . '...');
    
    $params = array();
	$lines = file($filename);
    $contents = '';
	for($i = 0 ; $i < count($lines)-1 ; $i++)
		$contents .= $lines[$i];
	
    $handle = popen('/var/www/banana_bank/.exe/set_trans_file ' . $filename, 'r');

    //getting contents of file (except scs_token) in case it needs to be hashed for SCS

	$words = $contents;
		
	while ($s = fgets($handle)) {
		array_push($params, $words);
		$words = str_word_count($s, 1, '1234567890');
	}
	
	pclose($handle);

	
	return $params;
}

function check_pass($pass) {
	
	$uppercase = preg_match('@[A-Z]@', $pass);
	$lowercase = preg_match('@[a-z]@', $pass);
	$number    = preg_match('@[0-9]@', $pass);

	if(!$uppercase || !$lowercase || !$number || strlen($pass) < 6)
		return false;
	else
		return true;
}

function send_attachment($to,$subject,$body,$filename){

$random_hash = md5(date('r', time())); 
$headers = "From: noreply.mybank@gmail.com"; 
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
$attachment = chunk_split(base64_encode(file_get_contents($filename))); 
ob_start();
?> 
--PHP-mixed-<?php echo $random_hash; ?>  
Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>" 

--PHP-alt-<?php echo $random_hash; ?>  
Content-Type: text/html; charset="iso-8859-1" 
Content-Transfer-Encoding: 7bit

<?php echo $body; ?>

--PHP-alt-<?php echo $random_hash; ?>-- 

--PHP-mixed-<?php echo $random_hash; ?>  
Content-Type: application/zip; name="<?php echo basename($filename); ?>"  
Content-Transfer-Encoding: base64  
Content-Disposition: attachment  

<?php echo $attachment; ?> 
--PHP-mixed-<?php echo $random_hash; ?>-- 

<?php 

$message = ob_get_clean(); 

mail($to, $subject, $message, $headers ); 
}


?>
