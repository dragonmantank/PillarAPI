<?php

/**
 * Exception for Nonexistant Resources
 * Thrown when a Resource doesn't exist. This is not meant to represent a
 * request that just generates an empty response, like a GET request with
 * an empty resultset
 *
 * @author Chris Tankersley <chris@ctankersley.com>
 * @copyright 2010 Chris Tankersley
 * @package Pillar_Rest_Exception
 */
class Pillar_Rest_Exception_NonexistantResource extends Exception
{
    
}
