<?php
use Framework\Routing\Request;

require_once __DIR__ . '/../Includes.php';

/**
 * Class RequestTests
 */
class RequestTests extends PHPUnit_Framework_TestCase
{
    public function testRequestGetsPopulatedWhenNull()
    {
        $request = new Request();

        $this->assertSame($_REQUEST, $request->getRequest());
    }
}