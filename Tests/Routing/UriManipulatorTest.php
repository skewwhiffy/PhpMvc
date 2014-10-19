<?php
require_once __DIR__ . '/../../Framework/Includes.php';
use Framework\Routing\UriManipulator;

/**
 * Class UriManipulatorTest
 */
class UriManipulatorTest extends PHPUnit_Framework_TestCase
{
    /** @var UriManipulator */
    private $manipulator;

    public function setUp()
    {
        $this->manipulator = new UriManipulator();
    }

    public function testSplitSplitsUri()
    {
        $uri = '//firstPart//secondPart//thirdPart//';

        $result = $this->manipulator->split($uri);

        $this->assertThat(sizeof($result), $this->equalTo(3));
        $this->assertThat($result[0], $this->equalTo('firstPart'));
        $this->assertThat($result[1], $this->equalTo('secondPart'));
        $this->assertThat($result[2], $this->equalTo('thirdPart'));
    }

    public function testSplitIgnoresMultipleSlashing()
    {
        $uri = '//////firstPart////////secondPart';

        $result = $this->manipulator->split($uri);

        $this->assertThat(sizeof($result), $this->equalTo(2));
        $this->assertThat($result[0], $this->equalTo('firstPart'));
        $this->assertThat($result[1], $this->equalTo('secondPart'));
    }

    public function testGetExtensionReturnsExtension()
    {
        $uri = '//firstPart//fileName.jpg';

        $result = $this->manipulator->getExtension($uri);

        $this->assertThat($result, $this->equalTo('jpg'));
    }

    public function testGetExtensionReturnsEmptyStringForNoExtension()
    {
        $uri = '//firstPart//secondPart';

        $result = $this->manipulator->getExtension($uri);

        $this->assertThat($result, $this->equalTo(''));
    }
}