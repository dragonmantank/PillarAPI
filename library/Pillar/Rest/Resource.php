<?php

/**
 * Resource that can be interacted with
 * Router will attempt to access the following functions:
 *   - put
 *   - get
 *   - post
 *   - delete
 *
 * @author Chris Tankersley
 * @copyright 2010 Chris Tankersley <chris@ctankersley.com>
 * @package Pillar_Rest
 */
abstract class Pillar_Rest_Resource
{
    /**
     * Response
     * @var Pillar_Rest_Response
     */
    protected $response;

    /**
     * Returns the response
     * @return Pillar_Rest_Response
     */
    public function getResponse()
    {
        if($this->response !== null) {
            return $this->response;
        }

        throw new Exception('No response has been set');
    }

    /**
     * Initializes the resource
     */
    public function init()
    {
        $this->response = new Pillar_Rest_Response();
    }

    /**
     * Runs the request against the resource
     * Attempts to call the appropriate verb on the resource
     *
     * @param Pillar_Rest_Request $request
     * @return Pillar_Rest_Response
     */
    public function run(Pillar_Rest_Request $request)
    {
        $this->init();
        
        $action = $request->getMethod();
        if(method_exists($this, $action)) {
            $this->$action($request);
        } else {
            $this->response->setCode(405);
            $this->response->setBody('Resource '.get_class($this).' does not support the '.strtoupper($request->getMethod()).' method');
        }

        return $this->getResponse();
    }
}
