<?php

namespace Framework\ViewRendering;

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
}