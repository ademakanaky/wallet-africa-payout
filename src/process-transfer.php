<?php

require 'Payout.php';

if(!isset($_POST['submit'])){
    echo "Method GET not supported.";
    exit();
}

//$bankname = trim($_POST['bankname']);
$bankcode = trim($_POST['bankcode']);
$accno = trim($_POST['accno']);
$accname = trim($_POST['accname']);
$amount = trim($_POST['amount']);

// perform transfer using submitted parameters
$txn = new \UCOM\Payout($bankcode, $accno, $accname,$amount);
echo $txn->initiateTransfer();
