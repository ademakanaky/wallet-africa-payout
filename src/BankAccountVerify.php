<?php

namespace UCOM;

class BankAccountVerify
{
    private $api_secret;
    private $api_public;
    private $url;
    private $url2;
    public function __construct(Array $key,$endpoint1, $endpoint2)
    {
        $this->api_secret = $key['api_secret'];
        $this->api_public = $key['api_public'];
        $this->url = $endpoint1;
        $this->url2 = $endpoint2;
    }

    public function curl($url, $body)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $body,
          CURLOPT_HTTPHEADER => array(
            "authorization: Bearer ". $this->api_public,
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
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

    /**
     * @param $AccountNo
     * @param $BankCode
     * @return string
     */
    public function fetchAccount($BankCode, $AccountNo){
       ///resolve account number
        $params = "AccountNumber=". $AccountNo . "&BankCode=" . $BankCode . "&SecretKey=" . $this->api_secret ;
        $url = $this->url2;
        $response = $this->curl($url, $params);
        $resp =  json_decode(json_decode($response,true));
        if(!is_null($resp->AccountName))
            return $resp->AccountName;
        else
            return null;
    }

    public function fetchBanks(){
        ///fetch all banks
        $body = "Secret%20Key=" . $this->api_secret;
        $response = $this->curl($this->url, $body);
        ///update banks.json file
        file_put_contents('banks.json', $response);
        return json_decode($response);
    }

    public function fetchBankCode($bankname){
        $code = "No code found";
        $banks = $this->fetchBanks();
        //return json_encode($banks);
        foreach(json_decode($banks) as $bank)
        {
            if($bank->BankName === $bankname)
             {
                 $code = $bank->BankCode;
             }
        }

        return $code;

    }
}