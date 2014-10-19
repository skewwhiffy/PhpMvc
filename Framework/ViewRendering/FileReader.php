<?php

namespace Framework\ViewRendering;

use Framework\Common\IPathExtensions;
use Framework\Common\PathExtensions;

/**
 * Class FileReader
 * @package Framework\ViewRendering
 */
class FileReader implements IFileReader
{
    /** @var string */
    private $directory;

    /** @var IPathExtensions */
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
}