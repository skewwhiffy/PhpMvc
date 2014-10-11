<?php
namespace Framework\Exceptions;

use Exception;

/**
 * Class TagWithNoContentException
 */
class TagWithNoContentException extends Exception
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct("No contents in tag found");
    }
}