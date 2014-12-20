<?php

namespace Framework\FileIo;

/**
 * Interface IFileReader
 * @package Framework\ViewRendering
 */
interface IFileIoWrapper
{
    /**
     * @param $path
     */
    public function requireOnce($path);

    /**
     * @param string $key
     * @param string $value
     */
    public function header($key, $value);

    /**
     * @param string $path
     */
    public function readFile($path);
}