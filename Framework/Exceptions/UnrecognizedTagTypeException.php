<?php
namespace Framework\Exceptions;

use Exception;
use Framework\Templating\Tags\IViewTag;

/**
 * Class UnrecognizedTagTypeException
 * @package Framework\Exceptions
 */
class UnrecognizedTagTypeException extends Exception
{
    /**
     * @param IViewTag $tag
     */
    public function __construct($tag)
    {
        $contents = $tag->getContents();
        parent::__construct("Contents are: '$contents'");
    }

    /**
     * @return string
     */
    public static function getClassName()
    {
        return __CLASS__;
    }
} 