<?php

namespace Framework\ViewRendering;

use Framework\Common\PathExtensions;
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

    /**
     * @param string $directory
     * @param null   $extensions
     */
    public function __construct($directory, $extensions = null)
    {
        if (empty($extensions))
        {
            $extensions = new PathExtensions();
        }
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
     * @param $path
     */
    function includeFile($path)
    {
        require_once $this->extensions->joinPaths($this->directory, $path);
    }
}