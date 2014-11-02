<?php namespace Framework\Exceptions;

use Exception;

/**
 * Class InvalidRequestMethod
 * @package Framework\Exceptions
 */
class InvalidRequestMethodException extends Exception{
    /**
     * @param string $method
     */
    public function __construct($method){
        parent::__construct("Unrecognized method: $method");
    }


    /**
     * @return string
     */
    public static function getClassName()
    {
        return __CLASS__;
    }
}