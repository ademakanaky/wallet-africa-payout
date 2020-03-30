<?php

require 'Payout.php';

$instanceV = new \UCOM\Payout('First Bank Plc','3038739704','Test Account','1600');

$details = $instanceV->initiateTransfer();

echo $details;

echo "<br>";

$status = $instanceV->fetchTransferStatus('7ujdwuigwifwugj');

echo $status;
