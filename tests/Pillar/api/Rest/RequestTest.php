<?php

class Pillar_Api_Rest_RequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * Sets up basic data for a request
     */
    public function setUp()
    {
        $_GET = array('Name' => 'Bob');
        $_POST = array('Name' => 'Jill');
    }

    /**
     * Makes sure that a Get request is being generated
     */
    public function testMethodGet()
    {
        $raw = array('REQUEST_METHOD' => 'GET');
        $request = new Pillar_Rest_Request($raw);

        $this->assertEquals(Pillar_Rest_Request::METHOD_GET, $request->getMethod());
    }

    /**
     * Makes sure that a Post request is being generated
     */
    public function testMethodPost()
    {
        $raw = array('REQUEST_METHOD' => 'POST');
        $request = new Pillar_Rest_Request($raw);

        $this->assertEquals(Pillar_Rest_Request::METHOD_POST, $request->getMethod());
    }

    /**
     * Makes sure that a Put request is being generated
     */
    public function testMethodPut()
    {
        $raw = array('REQUEST_METHOD' => 'PUT');
        $request = new Pillar_Rest_Request($raw);

        $this->assertEquals(Pillar_Rest_Request::METHOD_PUT, $request->getMethod());
    }

    /**
     * Makes sure that a Delete request is being generated
     */
    public function testMethodDelete()
    {
        $raw = array('REQUEST_METHOD' => 'DELETE');
        $request = new Pillar_Rest_Request($raw);

        $this->assertEquals(Pillar_Rest_Request::METHOD_DELETE, $request->getMethod());
    }

    /**
     * Makes sure that the $_GET global is being parsed for the data
     */
    public function testGetGetArray()
    {
        $raw = array('REQUEST_METHOD' => 'GET');
        $request = new Pillar_Rest_Request($raw);

        $vars = $request->getRequestVars();

        $this->assertEquals(1, count($vars));
        $this->assertEquals('Bob', $vars['Name']);
    }

    /**
     * Makes sure that the $_POST global is being parsed for the data
     */
    public function testGetPostArray()
    {
        $raw = array('REQUEST_METHOD' => 'POST');
        $request = new Pillar_Rest_Request($raw);

        $vars = $request->getRequestVars();

        $this->assertEquals(1, count($vars));
        $this->assertEquals('Jill', $vars['Name']);
    }

    public function testGetRequestUri()
    {
        $raw = array(
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/user?data=blah',
            'SCRIPT_NAME' => '/index.php',
        );

        $request = new Pillar_Rest_Request($raw);

        $this->assertEquals('user', $request->getUri());
    }

    public function testGetRequestUriWithFilename()
    {
        $raw = array(
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/index.php/user',
            'SCRIPT_NAME' => '/index.php',
        );

        $request = new Pillar_Rest_Request($raw);

        $this->assertEquals('user', $request->getUri());
    }

    public function testGetIdentifierWithVariablesWithFilename()
    {
        $raw = array(
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/index.php/user/1',
            'SCRIPT_NAME' => '/index.php',
        );

        $request = new Pillar_Rest_Request($raw);

        $this->assertEquals('1', $request->getIdentifier());
    }

    public function testGetIdentifierWithVariables()
    {
        $raw = array(
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/user/1',
            'SCRIPT_NAME' => '/index.php',
        );

        $request = new Pillar_Rest_Request($raw);

        $this->assertEquals('1', $request->getIdentifier());
    }

    public function testGetRequestUriWithoutBasename()
    {
        $raw = array(
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/yar',
            'SCRIPT_NAME' => '/index.php',
        );

        $request = new Pillar_Rest_Request($raw);

        $this->assertEquals('yar', $request->getUri());
    }

    public function testThrowMissingResourceException()
    {
        $this->setExpectedException('Pillar_Rest_Exception_NoResourceSpecified');
        $raw = array(
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/',
            'SCRIPT_NAME' => '/index.php',
        );

        $request = new Pillar_Rest_Request($raw);

        $this->assertEquals('yar', $request->getUri());
    }

    public function testGetQuery()
    {
        $raw = array(
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/',
            'SCRIPT_NAME' => '/resource?name=Bob&id=4',
        );

        $request = new Pillar_Rest_Request($raw);

        $this->assertEquals(array('name'=>'Bob', 'id'=>'4'), $request->getQuery());
    }
}
