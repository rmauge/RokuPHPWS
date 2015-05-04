<?php
namespace RokuWS;

/**
 * Class TransactionXMLResponse
 * @package RokuWS
 * Concrete class that handles XML responses.
 */
class TransactionXMLResponse extends BaseResponse
{

    public function __construct($responseRaw)
    {
        parent::__construct($responseRaw, 'text/xml');
    }

    protected function parseResponse($contentType)
    {
        libxml_use_internal_errors(true);
        $xmlResponse = simplexml_load_string($this->responseRaw);

        if ($xmlResponse !== false) {
            if ($xmlResponse->status == "Success") {
                $this->isSuccessful = true;
            }

            $arrayResponse = array();
            foreach ($xmlResponse->children() as $child) {
                $arrayResponse[$child->getName()] = (string)$child;
            }

            $this->parsedResponse = $arrayResponse;
            return $arrayResponse;

        } else {
            $errors = '';
            foreach (libxml_get_errors() as $error) {
                $errors . "\t" . $error->message . "\n";
            }
            throw new \RuntimeException("Error while parsing XML response: $errors");
        }
    }
}