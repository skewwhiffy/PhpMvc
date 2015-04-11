<?php
use Framework\Common\PathExtensions;
use Framework\FileIo\FileReader;

/**
 * Class ContentController
 */
class ContentController
{
    private $paths;
    private $files;

    /**
     *
     */
    public function __construct()
    {
        $this->paths = new PathExtensions();
        $this->files = new FileReader(__DIR__ . '/../Content');
    }

    public function __catchAll()
    {
        $args = func_get_args();
        $first = $args[0];
        $pathArray = $args[1];
        array_unshift($pathArray, $first);
        $filePath = $this->paths->joinPaths($pathArray);
        $this->files->serveFile($filePath);
    }
}