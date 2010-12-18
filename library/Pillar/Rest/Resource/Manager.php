<?php

class Pillar_Rest_Resource_Manager
{
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
        $name = 'Resource_'.$resource;
        $filename = 'Resource/'.$resource.'.php';

        if(static::fileExists($filename)) {
            $class = new $name;
            return $class;
        }

        throw new Pillar_Rest_Exception_NonexistantResource('Resource '.$resource.' does not exist');
    }

    public static function fileExists($filename)
    {
        $paths = explode(PATH_SEPARATOR, get_include_path());
        foreach ($paths as $path) {
            if (substr($path, -1) == DIRECTORY_SEPARATOR) {
                $fullpath = $path.$filename;
            } else {
                $fullpath = $path.DIRECTORY_SEPARATOR.$filename;
            }
            if (file_exists($fullpath)) {
                return $fullpath;
            }
        }
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
}