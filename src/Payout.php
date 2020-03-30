<?php

namespace UCOM;

require dirname(dirname(__FILE__)). DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use UCOM\BankAccountVerify;

class Payout
{

    private $bankName;
    private $account;
    private $accountName;
    private $amount;
    private $initiateTransferEndpoint;
    private $checkBalanceEndpoint;
    private $fetchTransferStatusEndpoint;
    private $fetchBanksEndpoint;
    private $resolveAccountEndpoint;
    private $currency;
    protected $secret;
    protected $pubKey;

    public function __construct($bankName, $accountNo, $accountName, $amount)
    {
        $this->bankName = $bankName;
        $this->account = $accountNo;
        $this->amount = $amount;
        $this->accountName = $accountName;
        $config = require 'config.php';
        $this->currency = $config['currency'];
        if ($config['mode'] == 'test'){
            $this->secret = $config['test_api_secret_key'];
            $this->pubKey = $config['test_api_public_key'];
            $this->checkBalanceEndpoint = $config['balance_link'];
            $this->initiateTransferEndpoint = $config['transfer_link'];
            $this->fetchTransferStatusEndpoint = $config['transaction_details_link'];
            $this->fetchBanksEndpoint = $config['allBanks_link'];
            $this->checkBalanceEndpoint = $config['balance_link'];
            $this->resolveAccountEndpoint = $config['account_enquiry_link'];
        }else{
            $this->secret = $config['live_api_secret_key'];
            $this->pubKey = $config['live_api_public_key'];
        }
            
    }

    private function api_keys(){
        if (isset($this->secret) && isset($this->pubKey)) {
            return [
                'api_secret' => $this->secret, 
                'api_public' => $this->pubKey
            ];
        }
    }

    private function InsufficientBalanceMessage(){
        return 'A friendly message should be sent to customer in this scenario.';
    }
    public function curl($url, $body = [])
    {
        $keys = $this->api_keys();
        $secret = $keys['api_secret'];
        $public = $keys['api_public'];
        $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_VERBOSE => TRUE,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer ". $public,
                    "cache-control: no-cache",
                ),
            ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_encode($response);

        }
    }

    private function validateAccount()
    {
        $iv = (new BankAccountVerify($this->api_keys(),$this->fetchBanksEndpoint,$this->resolveAccountEndpoint));
        $bankCode = $iv->fetchBankCode($this->bankName);
        //die($bankCode);
        $accName = $iv->fetchAccount($bankCode, $this->account);
        //die($accName);
        if ($accName == $this->accountName) {
            ///account details provided is valid
            $data = array('code'=> $bankCode, 'accname' => $accName);
            return json_encode($data);
        }else{
            ////account details provided is invalid
            return 'Could not resolve account at the moment';
        }
    }

    private function genTranxRef($length)
    {
        //return TransactionRefGen::getHashedToken();
         $token = "";
         $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
         $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
         $codeAlphabet.= "0123456789";
         $max = strlen($codeAlphabet);
    
        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max-1)];
        }
    
        return $token;
    }

    public function initiateTransfer()
    {
        /// Only transfer if there is enough balance.
        /// So there is a need to check balance before initiating transfer. You should uncomment the /// code block below

        /*if (!$this->canTransfer()) {
            return $this->InsufficientBalanceMessage();
        }*/

        //save this $transref in db transactions table alongside other details of the transaction
        $transref = uniqid();
        $resolveAccount = json_decode($this->validateAccount(),true);
        //die($resolveAccount);
        if ($resolveAccount === "Could not resolve account at the moment.") {
            
            return "Account name not correct. Please check and try again";
        }

        $url = $this->initiateTransferEndpoint;
        $body = "BankCode=". $resolveAccount['code'] . "&SecretKey=" . $this->secret. "&AccountNumber=". $this->account . "&AccountName=". $resolveAccount['accname']. "&TransactionReference=". $transref . "&Amount=". $this->amount;
        $transferResponse = $this->curl($url, $body);
        $res = json_decode(json_decode($transferResponse));
        $ResponseCode = $res->ResponseCode;
        $message = $res->Message;
        if (is_null($ResponseCode) || $ResponseCode != 100) {
            return "Transaction not successful. Please try again.";
        }
        
        return $message;
    }

    public function canTransfer()
    {
        $url = $this->checkBalanceEndpoint;
        $body = "Currency=". $this->currency ."&SecretKey=". $this->secret;
        $balance = $this->curl($url, $body);
        $balance = json_decode(json_decode($balance,true));
        $transferFee = 15;
        $amountPlusTransferFee = $this->amount + $transferFee;
        $balancec = $balance->Data->WalletBalance;

        if ($balancec <= $amountPlusTransferFee){
            return false;
        }
        return true;
    }

    public function fetchTransferStatus(String $transactionId)
    {
        
        $url = $this->fetchTransferStatusEndpoint;
        $body = "SecretKey=".$this->api_secret."&TransactionReference=" . $transactionId;
        $status = $this->curl($url, $body);
        
        $status = json_decode(json_decode($status));// update transaction status in db
        /*$sql3 = "UPDATE 'transaction' SET 'trans_status' = {$status},'transaction_time' = {$transtime} WHERE 'transaction_reference' = {$paystackTransCode}";
        ///run $sql above
        return "Status Updated successfully.";*/
        return $status->Message;
    }

    public function listenForNotification()
    {
        // TODO: Implement listenForNotification() method.
    }
}