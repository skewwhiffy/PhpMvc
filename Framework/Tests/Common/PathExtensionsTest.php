<?php
use Framework\Common\PathExtensions;

/**
 * Class PathExtensionsTest
 */
class PathExtensionsTest extends PHPUnit_Framework_TestCase
{
    /** @var  PathExtensions */
    private $extensions;

    public function setUp()
    {
        $this->extensions = new PathExtensions();
    }

    public function testJoinsPathsWithBackSlashes()
    {
        $slash = DIRECTORY_SEPARATOR;
        $first = 'C:\\blah1\\';
        $second = '\\blah2\\';

        $result = $this->extensions->joinPaths($first, $second);

        $this->assertThat($result, $this->equalTo('C:'.$slash.'blah1'.$slash.'blah2'));
    }

    public function testJoinsPathsWithForwardSlashes()
    {
        $slash = DIRECTORY_SEPARATOR;
        $first = 'C:/blah1/';
        $second = '/blah2/';

        $result = $this->extensions->joinPaths($first, $second);

        $this->assertThat($result, $this->equalTo('C:'.$slash.'blah1'.$slash.'blah2'));
    }
}