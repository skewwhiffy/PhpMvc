<?php namespace Framework\Exceptions;

use Exception;

/**
 * Class TagWithNoContentException
 * @package Framework\Exceptions
 */
class OpenTagNotClosedException extends Exception
{
    /**
     * @param string $code
     */
    public function __construct($code = null)
    {
        $message = 'Open tag with no close tag found.';
        if (!empty($code))
        {
            $message .= " Code is '$code'";
        }
        parent::__construct($message);
    }
}