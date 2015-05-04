<?php
namespace RokuWS;

/**
 * Class RokuWS
 * @package RokuWS
 *
 * Main class for communicating with the Roku Web Service API: http://sdkdocs.roku.com/display/sdkdoc/Web+Service+API
 * An API key is needed to use the service and can be obtained by registering at https://www.roku.com/developer
 */
class RokuWS
{

    const SDK_VERSION = '1.0';
    protected $API_USER_AGENT_PREFIX = "RokuWSPHP";
    protected $API_USER_AGENT = '';
    const API_DOMAIN = "https://apipub.roku.com/";
    const API_SERVICE_TRANSACTION_PATH = "listen/transaction-service.svc/";
    const API_ACTION_VALIDATE_TRANSACTION_PATH = "validate-transaction/";
    const API_ACTION_VALIDATE_REFUND_PATH = "validate-refund/";
    const API_ACTION_CANCEL_SUBSCRIPTION_PATH = "cancel-subscription";
    const API_ACTION_REFUND_SUBSCRIPTION_PATH = "refund-subscription";
    const API_ACTION_UPDATE_BILL_CYCLE_PATH = "update-bill-cycle";
    const API_ACTION_ISSUE_SERVICE_CREDIT_PATH = "issue-service-credit";
    protected static $API_SERVICE_TRANSACTION_PREFIX = '';

    protected $apiKey = null;

    public function __construct($apiKey)
    {
        if (empty($apiKey)) {
            throw new \InvalidArgumentException("An api key is needed for all requests to Roku");
        }

        if (!extension_loaded('curl')) {
            throw new \RuntimeException('The curl extension is not loaded and is needed to make http calls to Roku');
        }

        $this->API_USER_AGENT = $this->API_USER_AGENT_PREFIX . ' ' . RokuWS::SDK_VERSION;
        RokuWS::$API_SERVICE_TRANSACTION_PREFIX = RokuWS::API_DOMAIN . RokuWS::API_SERVICE_TRANSACTION_PATH;
        $this->apiKey = $apiKey;
    }

    public function getVersion()
    {
        return RokuWS::SDK_VERSION;
    }

    /**
     * @param $transId Roku transaction id to be validated
     * @return TransactionXMLResponse
     * @throws \Exception
     */
    public function validateTransaction($transId)
    {

        $url = RokuWS::$API_SERVICE_TRANSACTION_PREFIX . RokuWS::API_ACTION_VALIDATE_TRANSACTION_PATH . "$this->apiKey/$transId";
        $response = $this->doRequest($url);
        $rokuResponse = new TransactionXMLResponse($response);
        return $rokuResponse;
    }

    /**
     * @param $transId Roku transaction id (refund) to be validated
     * @return TransactionXMLResponse
     * @throws \Exception
     */
    public function validateRefund($transId)
    {

        $url = RokuWS::$API_SERVICE_TRANSACTION_PREFIX . RokuWS::API_ACTION_VALIDATE_REFUND_PATH . "$this->apiKey/$transId";
        $response = $this->doRequest($url);
        $rokuResponse = new TransactionXMLResponse($response);
        return $rokuResponse;
    }

    /**
     * @param $transId Roku subscription transaction id to be cancelled
     * @param $cancellationDate
     * @param $partnerReferenceId Id in external system managed by channel vendor
     * @return TransactionXMLResponse
     * @throws \Exception
     */
    public function cancelSubscription($transId, $cancellationDate, $partnerReferenceId = '') {
        $url = RokuWS::$API_SERVICE_TRANSACTION_PREFIX . RokuWS::API_ACTION_CANCEL_SUBSCRIPTION_PATH;

        $root = new \SimpleXMLElement('<cancel></cancel>');
        $root->addChild('partnerAPIKey', $this->apiKey);
        $root->addChild('transactionId', $transId);
        $root->addChild('cancellationDate', $cancellationDate);
        $root->addChild('partnerReferenceId', $partnerReferenceId);
        $response = $this->doRequest($url, $root->asXML());

        $rokuResponse = new TransactionXMLResponse($response);
        return $rokuResponse;
    }

    /**
     * @param $transId Roku subscription transaction id (refund) to be refunded
     * @param $amount
     * @param $partnerReferenceId Id in external system managed by channel vendor
     * @param $comments Ex. Reason for refund
     * @return TransactionXMLResponse
     * @throws \Exception
     */
    public function refundSubscription($transId, $amount, $partnerReferenceId = '', $comments = '') {
        $url = RokuWS::$API_SERVICE_TRANSACTION_PREFIX . RokuWS::API_ACTION_REFUND_SUBSCRIPTION_PATH;

        $root = new \SimpleXMLElement('<cancel></cancel>');
        $root->addChild('partnerAPIKey', $this->apiKey);
        $root->addChild('transactionId', $transId);
        $root->addChild('amount', $amount);
        $root->addChild('partnerReferenceId', $partnerReferenceId);
        $root->addChild('comments', $comments);
        $response = $this->doRequest($url, $root->asXML());

        $rokuResponse = new TransactionXMLResponse($response);
        return $rokuResponse;
    }

    /**
     * @param $transId
     * @param $newBillCycleDate
     * @return TransactionJSONResponse
     * @throws \Exception
     */
    public function updateBillCycle($transId, $newBillCycleDate) {
        $url = RokuWS::$API_SERVICE_TRANSACTION_PREFIX . RokuWS::API_ACTION_UPDATE_BILL_CYCLE_PATH ;

        $request = array("partnerAPIKey" => "$this->apiKey", "newBillCycleDate" => "$newBillCycleDate", "transactionId" => "$transId");
        $jsonRequest = json_encode($request);

        if (($errorNum = json_last_error()) === JSON_ERROR_NONE) {
            $response = $this->doRequest($url, $jsonRequest, 'application/json');
            $rokuResponse = new TransactionJSONResponse($response);
            return $rokuResponse;
        } else {
            throw new \Exception("Error encoding JSON, error number: $errorNum ");
        }
    }

    /**
     * @param $amount
     * @param $channelId
     * @param $rokuCustomerId
     * @param $comments
     * @param string $partnerReferenceId
     * @param string $productId
     * @return TransactionJSONResponse
     * @throws \Exception
     */
    public function issueServiceCredit($amount, $channelId, $rokuCustomerId, $comments = "", $partnerReferenceId = "", $productId = "") {
        $url = RokuWS::$API_SERVICE_TRANSACTION_PREFIX . RokuWS::API_ACTION_ISSUE_SERVICE_CREDIT_PATH ;

        $request = array("partnerAPIKey" => "$this->apiKey", "amount" => "$amount", "channelId" => "$channelId",
                        "rokuCustomerId" => "$rokuCustomerId", "comments" => $comments, "partnerReferenceId" => "$partnerReferenceId", "productId" => "$productId");
        $jsonRequest = json_encode($request);

        if (($errorNum = json_last_error()) === JSON_ERROR_NONE) {
            $response = $this->doRequest($url, $jsonRequest, 'application/json');
            $rokuResponse = new TransactionJSONResponse($response);
            return $rokuResponse;
        } else {
            throw new \Exception("Error encoding JSON, error number: $errorNum ");
        }
    }

    /**
     * @param $url
     * @return string
     * @throws \Exception
     */
    private function doRequest($url, $body = '', $contentType = 'text/xml', $httpMethod = 'POST')
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->API_USER_AGENT);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);

        if ($body) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $httpMethod);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            $headers = array(
                "Content-type: $contentType",
                "Accept: $contentType",
                "Content-length: " . strlen($body),
                "Connection: close",
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response) {
            return $response;
        } else {
            throw new \Exception("Error connecting to $url, curl error: $curlError, http code: $httpCode");
        }
    }
}