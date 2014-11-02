<?php namespace Framework\Exceptions;

use Exception;

/**
 * Class TagWithNoContentException
 */
class TagWithNoContentException extends Exception
{
    /**
     * @param string $code
     */
    public function __construct($code = null)
    {
        $message = 'No contents in tag found.';
        if (!empty($code))
        {
            $message .= " Code is '$code'";
        }
        parent::__construct($message);
    }
}