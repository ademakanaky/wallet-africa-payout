<?php

namespace UCOM\Test;

require dirname(dirname(__FILE__)). DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PHPUnit\Framework\TestCase;
use UCOM\BankAccountVerify;

class BankAccountVerifyTest extends TestCase {

    public function setUp()
    {
        $this->verify = new BankAccountVerify();
    }

    public function tearDown()
    {
        unset($this->verify);
    }

    public function testFetchAccount(){

        $inputBankName = 'First Bank';
        $inputAccountNo = 3038739704;
        $output = $this->verify->fetchAccount($inputBankName, $inputAccountNo);
        return $this->assertEquals('OLUWASEGUN ADEKUNLE IBIDOKUN',$output,'Account Name should match.');

    }
}