<?php
require "../vendor/autoload.php";

use RokuWS\RokuWS;

$ws = new RokuWS('<apiKey>');
$rokuResponse = $ws->validateTransaction('<transactionId>');

exit(0);