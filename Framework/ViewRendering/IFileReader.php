<?php

namespace Framework\ViewRendering;

use Framework\Common\IPathExtensions;

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
}