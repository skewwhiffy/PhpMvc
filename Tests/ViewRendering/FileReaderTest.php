<?php
require_once __DIR__ . '/../Includes.php';

use Framework\ViewRendering\FileReader;

/**
 * Class FileReaderTest
 */
class FileReaderTest extends PHPUnit_Framework_TestCase
{
    /** @var string */
    private $temporaryFilename;

    /** @var string */
    private $code;

    /** @var string */
    private $directoryName;

    /** @var string */
    private $fileName;

    /** @var resource */
    private $temporaryFile;

    public function setUp()
    {
        $this->temporaryFile = tmpFile();
        $this->temporaryFilename = stream_get_meta_data($this->temporaryFile)['uri'];
        $this->directoryName = dirname($this->temporaryFilename);
        $this->fileName = basename($this->temporaryFilename);
        $this->code = 'Hello world, this is a text file, oh yes';
        fwrite($this->temporaryFile, $this->code);
    }

    public function tearDown()
    {
        $this->deleteTemporaryFile();
    }

    public function testReadsTextFile()
    {
        $reader = new FileReader($this->directoryName);
        $result = $reader->readFile($this->fileName);

        $this->assertThat($result, $this->equalTo($this->code));
    }

    public function testReadNonExistentFileThrows()
    {
        $this->setExpectedException('Exception');
        $this->deleteTemporaryFile();

        $reader = new FileReader($this->directoryName);
        $reader->readFile($this->fileName);
    }

    private function deleteTemporaryFile()
    {
        $this->temporaryFile = null;
    }
}