<?php

namespace Framework\FileIo;

use Framework\Common\PathExtensions;
use Framework\Exceptions\FileNotFoundException;
use Framework\Exceptions\NotImplementedException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class FileReader
 * @package Framework\ViewRendering
 */
class FileReader implements IFileReader
{
    /** @var string */
    private $directory;

    /** @var PathExtensions */
    private $extensions;

    /** @var IFileIoWrapper */
    private $io;

    /**
     * @param string $directory
     * @param IFileIoWrapper $io
     * @param null $extensions
     * @throws NotImplementedException
     */
    public function __construct($directory, $io = null, $extensions = null)
    {
        if (empty($extensions))
        {
            $extensions = new PathExtensions();
        }
        if (empty($io))
        {
            $io = new FileIoWrapper();
        }
        $this->io = $io;
        $this->extensions = $extensions;
        $this->directory = $directory;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function readFile($path)
    {
        $fullPath = $this->extensions->joinPaths($this->directory, $path);
        return file_get_contents($fullPath);
    }

    /**
     * @return string[]
     */
    public function getFiles()
    {
        return $this->getFilesInternal();
    }

    /**
     * @param $path
     *
     * @return string[]
     */
    private function getFilesInternal($path = '')
    {
        $fullPath = $this->extensions->joinPaths($this->directory, $path);
        $subFolders = [];
        $files = [];
        $directoryIterator = new RecursiveDirectoryIterator(
            $fullPath,
            RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator(
            $directoryIterator,
            RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $file)
        {
            /** @noinspection PhpUndefinedMethodInspection */
            $returnPath = $this->extensions->joinPaths($path, $file->getFilename());
            /** @noinspection PhpUndefinedMethodInspection */
            if ($file->isDir())
            {
                $subFolders[] = $returnPath;
            }
            else
            {
                $files[] = $returnPath;
            }
        }

        foreach ($subFolders as $subPath)
        {
            foreach ($this->getFilesInternal($subPath) as $returnPath)
            {
                $files[] = $returnPath;
            }
        }

        return $files;
    }

    /**
     * @param string $path
     */
    function includeFile($path)
    {
        $this->io->requireOnce($this->extensions->joinPaths($this->directory, $path));
    }

    /**
     * @param string $path
     * @throws FileNotFoundException
     */
    function serveFile($path)
    {
        $fullPath = $this->extensions->joinPaths($this->directory, $path);
        if (!file_exists($fullPath))
        {
            var_dump($fullPath);
            throw new FileNotFoundException($fullPath);
        }
        $this->io->header('Content-Type', 'image/png');
        $this->io->readFile($fullPath);
    }
}