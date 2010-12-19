<?php

/** 
 * Exception for when an HTTP code is used in a Response that is not configured
 * Mostly for debugging. Since each HTTP code has a corresponding header line,
 * this helps catch codes that haven't been implemented yet
 *
 * @author Chris Tankersley <chris@ctankersley.com>
 * @copyright 2010 Chris Tankersley
 * @package Pillar_Rest_Exception
 */
class Pillar_Rest_Exception_UnknownHTTPCode extends Exception
{
    
}
