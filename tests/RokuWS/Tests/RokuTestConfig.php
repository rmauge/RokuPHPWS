<?php
namespace RokuWS\Tests;

/**
 * Class RokuTestConfig
 * @package RokuWS\Tests
 * Contains fixtures for running tests
 */
class RokuTestConfig
{

    public static function getConfig()
    {
        return array(
            'apiKey' => '<api-key>',
            'invalidTransactionId' => '11111111-1111-1111-1111-111111111111',
            'cancellationDate' => '2016-08-22T14:59:50',
            'partnerReferenceId' => '42',
            'newBillCycleTransId' => '1C63E500EF094DB4A83CA2CF00B7EB4E',
            'newBillCycleDate' => '2014-03-28',
            "creditAmount" => "5.00",
            "channelId" => "3801",
            "rokuCustomerId" => "AC4D2FD61F624451A61AQ2CF00A766A1"
        );
    }
}