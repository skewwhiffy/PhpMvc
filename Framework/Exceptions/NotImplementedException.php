<?php namespace Framework\Exceptions;

use Exception;

/**
 * Class NotImplementedException
 * @package Framework\Exceptions
 */
class NotImplementedException extends Exception
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}