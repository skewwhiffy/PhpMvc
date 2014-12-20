<?php
require_once __DIR__ . '/../Includes.php';

use Framework\FileIo\FileReader;
use Framework\FileIo\IFileIoWrapper;

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

    /** @var FileReader */
    private $reader;

    /** @var string */
    private $subdirectoryName;

    /** @var string */
    private $subdirectoryFilename;

    /** @var string */
    private $subcode;

    /** @var string */
    private $subdirectoryPartialFilename;

    /** @var IFileIoWrapper|PHPUnit_Framework_MockObject_MockObject */
    private $io;

    public function setUp()
    {
        $this->directoryName = tempnam(sys_get_temp_dir(), '');
        if (file_exists($this->directoryName))
        {
            unlink($this->directoryName);
        }
        mkdir($this->directoryName);

        $this->temporaryFilename = $this->directoryName . '\temporary.php';
        $this->code = 'Hello world, this is a text file, oh yes';
        file_put_contents($this->temporaryFilename, $this->code);
        var_dump($this->directoryName);

        $this->fileName = basename($this->temporaryFilename);
        $this->io = $this->getMock('Framework\FileIo\IFileIoWrapper');

        $this->reader = new FileReader($this->directoryName, $this->io);;

        $this->subdirectoryName = $this->directoryName . '\temporary';
        mkdir($this->subdirectoryName);
        $this->subcode = 'Subdirectory file, oh yes';
        $this->subdirectoryFilename = $this->subdirectoryName . '\subTemporary.php';
        $this->subdirectoryPartialFilename = 'temporary\subTemporary.php';
        file_put_contents($this->subdirectoryFilename, $this->subcode);

    }

    public function tearDown()
    {
        $this->deleteTemporaryFiles();
    }

    public function testReadsTextFile()
    {
        $result = $this->reader->readFile($this->fileName);

        $this->assertThat($result, $this->equalTo($this->code));
    }

    public function testReadsDirectoryContents()
    {
        $files = $this->reader->getFiles();

        $this->assertThat($files, $this->contains($this->fileName));
        $this->assertThat($files, $this->contains($this->subdirectoryPartialFilename));
    }

    public function testReadNonExistentFileThrows()
    {
        $this->setExpectedException('Exception');
        $this->deleteTemporaryFiles();

        $this->reader->readFile($this->fileName);
    }

    public function testIncludeWorks()
    {
        $reader = $this->getTestFilesReader();

        $reader->includeFile('FileToReadClass.php');

        $readClass = new FileToReadClass();
        $this->assertThat($readClass->testMethod(), $this->equalTo('hello world'));
    }

    public function testServeFileWorks()
    {
        $reader = $this->getTestFilesReaderWithStubbedIo();
        $this->io->expects($this->once())
            ->method('header');
        $this->io->expects($this->once())
            ->method('readFile');

        $reader->serveFile('FileToReadClass.php');
    }

    /**
     * @return FileReader
     */
    private function getTestFilesReader()
    {
        return new FileReader(__DIR__ . '/FileReaderTestFiles');
    }

    /**
     * @return FileReader
     */
    private function getTestFilesReaderWithStubbedIo(){
        return new FileReader(__DIR__ . '/FileReaderTestFiles', $this->io);
    }

    private function deleteTemporaryFiles()
    {
        $this->deleteDirectory($this->directoryName);
    }

    /**
     * @param string $dir
     */
    private function deleteDirectory($dir)
    {
        if (is_dir($dir))
        {
            $objects = scandir($dir);
            foreach ($objects as $object)
            {
                if ($object != "." && $object != "..")
                {
                    if (filetype($dir . "/" . $object) == "dir")
                    {
                        $this->deleteDirectory($dir . "/" . $object);
                    }
                    else
                    {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}