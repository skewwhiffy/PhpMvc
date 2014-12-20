<?php namespace Framework\Exceptions;

use Exception;

class FileNotFoundException extends Exception
{
    public function __construct($filePath)
    {
        parent::__construct("Could not find file: '$filePath'");
    }
}