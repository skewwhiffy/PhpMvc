<?php namespace Framework\Exceptions;

use Exception;

/**
 * Class FileTypeNotRecognizedException
 * @package Framework\Exceptions
 */
class FileTypeNotRecognizedException extends Exception
{
    /**
     * @param string $fileType
     */
    public function __construct($fileType)
    {
        parent::__construct("I don't recognize file type '$fileType'");
    }
}