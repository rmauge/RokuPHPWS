<?php
namespace RokuWS;

/**
 * Class TransactionJSONResponse
 * @package RokuWS
 * Concrete class that handles JSON responses.
 */
class TransactionJSONResponse extends BaseResponse
{

    public function __construct($responseRaw)
    {
        parent::__construct($responseRaw, 'application/json');
    }

    protected function parseResponse($contentType)
    {
        $jsonResponseAsArray = json_decode($this->responseRaw, true);

        if ($jsonResponseAsArray !== null) {
            if ($jsonResponseAsArray["status"] === 0)
            {
                $this->isSuccessful = true;
            }
            $this->parsedResponse = $jsonResponseAsArray;
            return $jsonResponseAsArray;
        } else {
            throw new \RuntimeException('Error while parsing JSON response. error number: ' . json_last_error());
        }
    }
}
