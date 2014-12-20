<?php

namespace Framework\FileIo;

/**
 * Interface IFileReader
 * @package Framework\ViewRendering
 */
interface IFileReader
{
    /**
     * @param string $path
     *
     * @return string
     */
    function readFile($path);

    /**
     * @return string[]
     */
    function getFiles();

    /**
     * @param string $path
     */
    function includeFile($path);

    /**
     * @param string $path
     */
    function serveFile($path);
}