<?php

/**
 * Used to find and create resource objects
 * Used to detect when no resource was specified
 *
 * @author Chris Tankersley <chris@ctankersley.com>
 * @copyright 2010 Chris Tankersley
 * @package Pillar_Rest_Exception
 */
class Pillar_Rest_Resource_Manager
{
    /**
     * Namespaces for Pillar to append to resource names
     * @var array
     */
    protected static $namespaces = array();

	/**
	 * Default resource that we need to fall back to
	 * @var string;
	 */
	protected static $defaultResource;

    /**
     * Adds a namespace into the stack
     * @param string $class
     * @param string $path
     */
    public static function addNamespace($class, $path)
    {
        static::$namespaces[$class] = $path;
    }

	/**
	 * Defines the default resource to drop to, if needed
	 * @param string $resource
	 */
	public static function defaultResource($resource)
	{
		static::$defaultResource = $resource;
	}

    /**
     * Returns a Resource object
     * @param mixed $resource
     * @return Pillar_Rest_Resource
     * @throws Pillar_Rest_Exception_NonexistantResource
     */
    public static function factory($resource)
    {
        if($resource instanceof Pillar_Rest_Request) {
            $resource = static::getResourceName($resource);
        }

        $resource = ucfirst($resource);

        $class = static::getResourceClass($resource);
        if($class !== null) {
            $object = new $class();
            return $object;
        }

		throw new Pillar_Rest_Exception_NonexistantResource('Resource '.$resource.' does not exist');
    }

    /**
     * Returns the currently registered namespaces
     * @return array
     */
    public static function getNamespaces()
    {
        return static::$namespaces;
    }

    /**
     * Returns the Resource name from a URI
     * @param Pillar_Rest_Request $request
     * @return string
     */
    public static function getResourceName(Pillar_Rest_Request $request)
    {
		try {
			$uri = $request->getUri();
		} catch(Pillar_Rest_Exception_NoResourceSpecified $e) {
			$uri = static::$defaultResource;
		}

        list($name) = explode('?', $uri);
        list($name) = explode('/', $name);

        return $name;
    }

    /**
     * Returns the full class name of a resource based upon the short name
     * Searches through all the namespaces that have been registered, and then
     * falls back to just looking for <name>.php . If nothing is found, then
     * null is returned.
     *
     * @param string $resource
     * @return string
     */
    public static function getResourceClass($resource)
    {
        $file = ucfirst($resource).'.php';

        $searches = array();
        foreach(static::$namespaces as $key => $value) {
            $searches[$key] = $value.$file;
        }

        foreach(explode(PATH_SEPARATOR, get_include_path()) as $dir) {
            foreach($searches as $key => $class) {
                if(is_file($dir.'/'.$class)) {
                    return $key.'_'.ucfirst($resource);
                }
            }
        }

        return null;
    }
}
