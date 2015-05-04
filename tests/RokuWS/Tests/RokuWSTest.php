<?php
namespace RokuWS\Tests;

use RokuWS\RokuWS;

class RokuWSTest extends \PHPUnit_Framework_TestCase
{

    protected static $config;

    public static function setUpBeforeClass()
    {
        RokuWSTest::$config = RokuTestConfig::getConfig();
    }

    /**
     * @test
     */
    public function isInvalidTransaction()
    {
        $ws = new RokuWS(RokuWSTest::$config["apiKey"]);
        $rokuResponse = $ws->validateTransaction(RokuWSTest::$config["invalidTransactionId"]);
        $this->assertFalse($rokuResponse->isSuccessful());
    }

    /**
     * @test
     */
    public function isInvalidRefundTransaction()
    {
        $ws = new RokuWS(RokuWSTest::$config["apiKey"]);
        $rokuResponse = $ws->validateRefund(RokuWSTest::$config["invalidTransactionId"]);
        $this->assertFalse($rokuResponse->isSuccessful());
    }

    /**
     * @test
     */
    public function isInvalidCancelTransaction()
    {
        $ws = new RokuWS(RokuWSTest::$config["apiKey"]);
        $rokuResponse = $ws->cancelSubscription(RokuWSTest::$config["invalidTransactionId"],
                                                RokuWSTest::$config["newBillCycleDate"],
                                                RokuWSTest::$config["partnerReferenceId"]);
        $this->assertFalse($rokuResponse->isSuccessful());
    }

    /**
     * @test
     */
    public function isInvalidUpdateBillCycle()
    {
        $ws = new RokuWS(RokuWSTest::$config["apiKey"]);
        $rokuResponse = $ws->updateBillCycle(RokuWSTest::$config["newBillCycleTransId"],
            RokuWSTest::$config["newBillCycleDate"]);
        $this->assertFalse($rokuResponse->isSuccessful());
    }

    /**
     * @test
     */
    public function isInvalidIssueServiceCredit()
    {
        $ws = new RokuWS(RokuWSTest::$config["apiKey"]);
        $rokuResponse = $ws->issueServiceCredit(RokuWSTest::$config["creditAmount"],
            RokuWSTest::$config["channelId"],
            RokuWSTest::$config["rokuCustomerId"]
            );
        $this->assertFalse($rokuResponse->isSuccessful());
    }
}