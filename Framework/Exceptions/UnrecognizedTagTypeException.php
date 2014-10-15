<?php
namespace Framework\Exceptions;

use Exception;
use Framework\Templating\Html\TagElement;

/**
 * Class UnrecognizedTagTypeException
 * @package Framework\Exceptions
 */
class UnrecognizedTagTypeException extends Exception
{
    /**
     * @param TagElement $tag
     */
    public function __construct($tag){

    }
} 