<?php

abstract class Pillar_Rest_Resource
{
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