<?php
namespace Framework\Exceptions;

use Exception;
use Framework\Templating\Html\IDocumentElement;

/**
 * Class UnrecognizedElementTypeException
 * @package Framework\Exceptions
 */
class UnrecognizedElementTypeException extends Exception
{
    /**
     * @param IDocumentElement $element
     */
    public function __construct($element)
    {
        $class = get_class($element);
        parent::__construct("Unrecognized element type: $class");
    }
} 