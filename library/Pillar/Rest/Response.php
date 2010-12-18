<?php

class Pillar_Rest_Response
{
    /**
     * Content type for JSON
     */
    const CONTENT_JSON = 'application/json';

    /**
     * Content type for HTML
     */
    const CONTENT_HTML = 'text/html';

    /**
     * Body of the response
     * @var string
     */
    protected $body;

    /**
     * Content type that will be sent to the browser
     * Should be one of the constants from this object
     * @var string
     */
    protected $contentType = 'application/json';

    /**
     * List of valid response codes to set
     * @var array
     */
    protected $responseCodes = array(
        200 => 'HTTP/1.0 200 OK',
        201 => 'HTTP/1.0 201 Created',
        500 => 'HTTP/1.0 500 Internal Server Error',
        404 => 'HTTP/1.0 404 Not Found',
        405 => 'HTTP/1.0 405 Method Not Allowed',
        501 => 'HTTP/1.0 501 Not Implemented',
    );

    /**
     * Response code that will be sent to the browser
     * @var int
     */
    protected $responseCode = 200;

    /**
     * Displays the response to the browser
     */
    public function display()
    {
        header($this->responseCodes[$this->responseCode]);
        header('Content-Type: '.$this->contentType);
        
        echo $this->body;
    }

    /**
     * Sets the body of the response
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Sets the response code for the response
     * @param int $code
     */
    public function setCode($code)
    {
        if(array_key_exists((int)$code, $this->responseCodes)) {
            $this->responseCode = $code;
        } else {
            throw new Pillar_Rest_Exception_UnknownHTTPCode('HTTP Code '.$code.' is not supported');
        }
    }

    /**
     * Sets the content type of the response
     * @param string $type
     */
    public function setContentType($type)
    {
        $this->contentType = $type;
    }
}