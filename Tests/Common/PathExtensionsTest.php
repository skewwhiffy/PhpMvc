<?php
require_once __DIR__ . '/../../Framework/Includes.php';
use Framework\Common\PathExtensions;
use Framework\Exceptions\FileTypeNotRecognizedException;

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

        $this->assertThat($result, $this->equalTo('C:' . $slash . 'blah1' . $slash . 'blah2'));
    }

    public function testJoinsPathsWithForwardSlashes()
    {
        $slash = DIRECTORY_SEPARATOR;
        $first = 'C:/blah1/';
        $second = '/blah2/';

        $result = $this->extensions->joinPaths($first, $second);

        $this->assertThat($result, $this->equalTo('C:' . $slash . 'blah1' . $slash . 'blah2'));
    }

    public function testSplitsPathsCorrectly()
    {
        $path = 'a/b\c';

        $result = $this->extensions->splitPath($path);

        $this->assertThat(count($result), $this->equalTo(3));
        $this->assertThat($result[0], $this->equalTo('a'));
        $this->assertThat($result[1], $this->equalTo('b'));
        $this->assertThat($result[2], $this->equalTo('c'));
    }

    public function testJoinWorksWithArray()
    {
        $slash = DIRECTORY_SEPARATOR;
        $first = 'C:/blah1/';
        $second = '/blah2/';
        $pathArray = [$first, $second];

        $result = $this->extensions->joinPaths($pathArray);

        $this->assertThat($result, $this->equalTo('C:' . $slash . 'blah1' . $slash . 'blah2'));
    }

    public function testGetExtensionWorks()
    {
        $extension = 'png';
        $path = "some/path/to/nowhere.$extension";

        $result = $this->extensions->getExtension($path);

        $this->assertThat($result, $this->equalTo('png'));
    }

    /**
     *
     */
    public function testGetMimeTypeWorks()
    {
        $testCases = [
            null => null,
            'js' => 'application/x-javascript',
            'json' => 'application/json',
            'jpg' => 'image/jpg',
            'jpeg' => 'image/jpg',
            'jpe' => 'image/jpg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'tiff' => 'image/tiff',
            'css' => 'text/css',
            'xml' => 'application/xml',
            'doc' => 'application/msword',
            'docx' => 'application/msword',
            'xls' => 'application/vnd.ms-excel',
            'xlt' => 'application/vnd.ms-excel',
            'xlm' => 'application/vnd.ms-excel',
            'xld' => 'application/vnd.ms-excel',
            'xla' => 'application/vnd.ms-excel',
            'xlc' => 'application/vnd.ms-excel',
            'xlw' => 'application/vnd.ms-excel',
            'xll' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pps' => 'application/vnd.ms-powerpoint',
            'rtf' => 'application/rtf',
            'pdf' => 'application/pdf',
            'html' => 'text/html',
            'htm' => 'text/html',
            'php' => 'text/html',
            'txt' => 'text/plain',
            'mpeg' => 'video/mpeg',
            'mpe' => 'video/mpeg',
            'mpg' => 'video/mpeg',
            'mp3' => 'audio/mpeg3',
            'wav' => 'audio/wav',
            'aiff' => 'audio/aiff',
            'aif' => 'audio/aiff',
            'avi' => 'video/msvideo',
            'wmv' => 'video/x-ms-wmv',
            'mov' => 'video/quicktime',
            'zip' => 'application/zip',
            'tar' => 'application/x-tar',
            'swf' => 'application/x-shockwave-flash'
        ];
        $notCorrect = [];
        foreach ($testCases as $key => $value)
        {
            try
            {
                $result = $this->extensions->getMimeType("test/file/name.$key");
                if ($result === $value)
                {
                    continue;
                }
            }
            catch (FileTypeNotRecognizedException $ex)
            {
            }
            $notCorrect[$key] = $value;
        }
        if (sizeof($notCorrect) === 0)
        {
            return;
        }
        $message = '';
        foreach ($notCorrect as $key => $value)
        {
            $message .= "Expected '$value' for extension '$key'";
            $message .= PHP_EOL;
        }
        $this->fail($message);
    }
}