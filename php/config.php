<?php

// Configurable variables

require_once __DIR__ . '/../phpsec/crypto/confidentialstring.php';

$DEBUG_MODE = false;
$SILENT_MODE = false;

$DB_HOST = 'localhost';
$DB_USER = phpsec\confidentialString(':sENSt7jtm5WBRy14P95atM8qa8ttFt0COQwkvyIKca8=');
$DB_PASS = phpsec\confidentialString(':ZaGjIXgNDKH668WPPZEMbSjFECBewKSjIYrykS0rMGk=');
$DB_NAME = phpsec\confidentialString(':1cLIvEkzWViP7sz5RdTDtzBuU5rjBwK47X+rmtYUJFY=');

$SESSION_DURATION = 300;
$PASSREC_TOKEN_DURATION = '02:00:00';
$SYSTEM_EMAIL = 'noreply.mybank@gmail.com';

?>
