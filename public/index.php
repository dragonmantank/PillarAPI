<?php

session_start();

ini_set('display_errors', 1);

set_include_path(implode(':', array(
    realpath(__DIR__.'/../library/'),
    get_include_path(),
)));

include_once 'SplClassLoader.php';
$loader = new SplClassLoader();
$loader->register();

try {
    $server = new Pillar_Rest_Server(new Pillar_Rest_Request());
    $server->dispatch();
} catch (Pillar_Rest_Exception_NoResourceSpecified $e) {
    $response = new Pillar_Rest_Response();
    $response->setCode(200);
    $response->setContentType(Pillar_Rest_Response::CONTENT_HTML);
    $response->setBody('Pillar API');

    $response->display();
} catch (Pillar_Rest_Exception_NonexistantResource $e) {
    $response = new Pillar_Rest_Response();
    $response->setCode(404);
    $response->setContentType(Pillar_Rest_Response::CONTENT_HTML);
    $response->setBody($e->getMessage());

    $response->display();
} catch (Exception $e) {
    $response = new Pillar_Rest_Response();
    $response->setCode(500);
    $response->setContentType(Pillar_Rest_Response::CONTENT_HTML);
    $response->setBody($e->getMessage()."\n".$e->getTraceAsString());

    $response->display();
}