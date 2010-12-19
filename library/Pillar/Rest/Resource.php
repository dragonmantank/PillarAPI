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
     * Runs the request against the resource
     * Attempts to call the appropriate verb on the resource
     *
     * @param Pillar_Rest_Request $request
     * @return Pillar_Rest_Response
     */
    public function run(Pillar_Rest_Request $request)
    {
        $action = $request->getMethod();
        if(method_exists($this, $action)) {
            return $this->$action($request);
        } else {
            $response = new Pillar_Rest_Response();
            $response->setCode(405);
            $response->setBody('Resource '.get_class($this).' does not support the '.strtoupper($request->getMethod()).' method');

            return $response;
        }
    }
}
