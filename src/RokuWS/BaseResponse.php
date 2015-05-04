<?php
namespace RokuWS;

/**
 * Class BaseResponse
 * @package RokuWS
 *
 * Base class that encapsulates a Roku Web Service response. The XML and JSON responses are only one level
 * so they are easily represented as a map.
 */
abstract class BaseResponse
{
    protected $isSuccessful = false;
    protected $responseRaw = '';
    protected $parsedResponse = '';
    protected $contentType = '';

    public function __construct($responseRaw = '', $contentType = '')
    {
        $this->responseRaw = $responseRaw;
        $this->contentType = $contentType;
        $this->parseResponse($contentType);
    }

    public function getRawResponse()
    {
        return $this->responseRaw;
    }

    /**
     * Implementers MUST update $parsedResponse.
     * @return array Response XML or JSON elements "flattened" to a map after parsing is completed.
     */
    public function getParsedResponse()
    {
        return $this->parsedResponse;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Implementers MUST update $isSuccessful
     * @return boolean Whether the response was successful or not
     */
    public function isSuccessful()
    {
        return $this->isSuccessful;
    }

    public function __toString()
    {
        return $this->responseRaw;
    }

    abstract protected function parseResponse($contentType);
}