<?php

/**
 * REST Server implementation
 * Takes (or creates) a request and routes it through the system
 *
 * @author Chris Tankersley
 * @copyright 2010 Chris Tankersley <chris@ctankersley.com>
 * @package Pillar_Rest
 */
class Pillar_Rest_Server
{
    /**
     * Object to dynamically build resource objects
     * @var Pillar_Rest_Resource_Manager
     */
    protected $resourceManager;

    /**
     * Request
     * @var Pillar_Rest_Request
     */
    protected $request;

    /**
     * Creates a server to process a request
     * @param Pillar_Rest_Request $request
     */
    public function __construct(Pillar_Rest_Request $request)
    {
        $this->request = $request;
    }

    /**
     * Executes the request
     */
    public function dispatch()
    {
        $resource = $this->getResource();
        $response = $resource->run($this->request);
        $response->display();
    }

    /**
     * Returns the resource object that is being called
     * @return Pillar_Rest_Resource
     */
    public function getResource()
    {
        $manager = $this->getResourceManager();
        return $manager::factory($this->request);
    }

    /**
     * Returns the current Action Manager
     * @return Pillar_Rest_Resource_Manager
     */
    public function getResourceManager()
    {
        if($this->resourceManager == null) {
            $this->resourceManager = new Pillar_Rest_Resource_Manager();
        }

        return $this->resourceManager;
    }

    /**
     * Allows manual setting of the Resource Manager to use
     * @param Pillar_Rest_Resource_Manager $manager
     */
    public function setResourceManager(Pillar_Rest_Resource_Manager $manager)
    {
        $this->resourceManager = $manager;
    }
}
