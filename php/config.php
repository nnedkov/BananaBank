<?php

// Configurable variables

require_once __DIR__ . '/../phpsec/crypto/confidentialstring.php';

$DEBUG_MODE = false;
$SILENT_MODE = false;

$DB_HOST = 'localhost';
$DB_USER = phpsec\confidentialString(':5JM/8u8PIfcc6LH87+jUeTuxWDkWiq3wx5dQojA0VPk=');
$DB_PASS = phpsec\confidentialString(':MAyW6SuWGwCIxYE4vflZt6sZX/i2ckbnxE3CW3188fQ=');
$DB_NAME = phpsec\confidentialString(':bXZ7ADSM4zGfj31sJwSOEq46KoCol0bglByqjhFXE8w=');

$SESSION_DURATION = 300;
$PASSREC_TOKEN_DURATION = '02:00:00';
$SYSTEM_EMAIL = 'noreply.mybank@gmail.com';
$MAX_FAIL_ATTEMPTS = 5;
$TIMEOUT_PERIOD = 1800;

?>
