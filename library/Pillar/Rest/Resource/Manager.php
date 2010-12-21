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
     * Adds a namespace into the stack
     * @param string $class
     * @param string $path
     */
    public static function addNamespace($class, $path)
    {
        static::$namespaces[$class] = $path;
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
        list($name) = explode('?', $request->getUri());
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
                echo "Searching for $dir"."$class<br>";
                if(is_file($dir.'/'.$class)) {
                    return $key.'_'.ucfirst($resource);
                }
            }
        }

        return null;
    }
}
