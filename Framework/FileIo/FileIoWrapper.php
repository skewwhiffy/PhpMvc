<?php

namespace Framework\FileIo;

/**
 * Class FileIoWrapper
 * @package Framework\FileIo
 */
class FileIoWrapper implements IFileIoWrapper
{
    /**
     * @param string $path
     */
    public function requireOnce($path){
        require_once $path;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function header($key, $value){
        header("$key: $value");
    }

    /**
     * @param string $path
     */
    public function readFile($path)
    {
        readfile($path);
    }
}