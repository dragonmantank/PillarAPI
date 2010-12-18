<?php

class Pillar_Api_Rest_Resource_ManagerTest extends PHPUnit_Framework_TestCase
{
    public function testGetResourceName()
    {
        $raw = array(
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/user?data=blah',
            'SCRIPT_NAME' => '/index.php',
        );

        $request = new Pillar_Rest_Request($raw);
        $manager = new Pillar_Rest_Resource_Manager();

        $this->assertEquals('user', $manager->getResourceName($request));
    }
}
