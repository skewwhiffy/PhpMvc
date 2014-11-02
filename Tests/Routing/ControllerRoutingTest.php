<?php

use Framework\Routing\ControllerRouting;
use Framework\Routing\Request;
use Framework\ViewRendering\FileReader;

require_once __DIR__ . '/../Includes.php';

/**
 * Class ControllerRoutingTest
 */
class ControllerRoutingTest extends PHPUnit_Framework_TestCase
{
    public function testConstructWorksWithNoArguments()
    {
        $routing = new ControllerRouting(new FileReader(__DIR__));
        $request = $routing->getRequest();

        $this->assertThat($request, $this->isInstanceOf(get_class(new Request())));
    }

    public function testHasMatchingControllerReturnsFalseIfNoMatchingControllers(){

    }
}