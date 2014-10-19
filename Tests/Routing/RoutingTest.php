<?php
require_once __DIR__ . '/../../Framework/Includes.php';
use Framework\Routing\Request;

/**
 * Class RoutingTest
 */
class RoutingTest extends PHPUnit_Framework_TestCase
{
    public function testGetUriWorks(){
        $uri = 'hello baby';
        $server = ['REQUEST_URI' => $uri];
        $routing = new Request($server);

        $result = $routing->getUri();

        $this->assertThat($result, $this->equalTo($uri));
    }
}