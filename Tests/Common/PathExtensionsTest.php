<?php
require_once __DIR__ . '/../../Framework/Includes.php';
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

    public function testSplitsPathsCorrectly(){
        $path = 'a/b\c';

        $result = $this->extensions->splitPath($path);

        $this->assertThat(count($result), $this->equalTo(3));
        $this->assertThat($result[0], $this->equalTo('a'));
        $this->assertThat($result[1], $this->equalTo('b'));
        $this->assertThat($result[2], $this->equalTo('c'));
    }
}