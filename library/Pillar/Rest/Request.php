<?php

/**
 * Request that has been sent to the server
 * Takes either an array or will user the $_SERVER global to dissect and
 * interpret. Exposes elements of the system request into an object.
 *
 * @author Chris Tankersley
 * @copyright 2010 Chris Tankersley <chris@ctankersley.com>
 * @package Pillar_Rest
 */
class Pillar_Rest_Request
{
    /**
     * Represents a GET Request
     */
    const METHOD_GET = 'get';

    /**
     * Represents a POST Request
     */
    const METHOD_POST = 'post';

    /**
     * Represents a DELETE Request
     */
    const METHOD_DELETE = 'delete';

    /**
     * Represents a PUT Request
     */
    const METHOD_PUT = 'put';

    /**
     * Raw request that is being encapsulated
     * Array keys supported:
     *   REQUEST_METHOD
     *
     * @var array
     */
    protected $raw_request;

    /**
     * Sets up a new request
     * If an array is passed, it should emulate the $_SERVER global. If no
     * array is passed, it will attempt to use $_SERVER.
     *
     * @param array $raw_request
     */
    public function __construct(array $raw_request = array())
    {
        if(empty($raw_request)) {
            $this->raw_request = $_SERVER;
        } else {
            $this->raw_request = $raw_request;
        }
    }

    /**
     * Returns a sanitized name for the request type that is being handled
     * @return string
     */
    public function getMethod()
    {
        return strtolower($this->raw_request['REQUEST_METHOD']);
    }

    /**
     * Returns the variables that were passed during the request
     * @return StdObj
     */
    public function getRequestVars()
    {
        switch($this->getMethod()) {
            case static::METHOD_DELETE:
                $obj = new stdClass();
                $obj->identifier = $this->getIdentifier();
                return $obj;
                break;
            case static::METHOD_GET:
                $data = $_GET;
                break;
            case static::METHOD_POST:
                $data = $_POST;
                break;
            case static::METHOD_PUT:
                parse_str(file_get_contents("php://input"),$data);
                break;
        }

        if(isset($data['data'])) {
            return json_decode($data['data']);
        }
    }

    public function getIdentifier()
    {
        $uri = $this->raw_request['REQUEST_URI'];
        return substr($uri, (strrpos($uri, '/')+1));
    }

    /**
     * Returns the URI that needs to be routed
     * @return string
     */
    public function getUri()
    {
        $script = $this->raw_request['SCRIPT_NAME'];
        $uri = $this->raw_request['REQUEST_URI'];
        $basename = basename($script);

        if(strpos($uri, $basename)) {
            $cleanUri = substr($uri, (strlen($script)+1));
        } else {
            if(strpos($uri, '?') === false) {
                $cleanUri = substr($uri, 1);
            } else {
                $cleanUri = substr($uri, 1, (strpos($uri, '?')-1));
            }
        }

        if($cleanUri == '') {
            throw new Pillar_Rest_Exception_NoResourceSpecified('No resource was specified');
        }
        
        return $cleanUri;
    }
}
