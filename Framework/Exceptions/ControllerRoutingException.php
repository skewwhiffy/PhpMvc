<?php namespace Framework\Exceptions;

use Exception;

/**
 * Class ControllerRoutingException
 * @package Framework\Exceptions
 */
class ControllerRoutingException extends Exception
{
    /**
     * @param string $controllerClassName
     */
    public function __construct($controllerClassName)
    {
        parent::__construct($this->routingMessage($controllerClassName));
    }

    /**
     * @param $controllerClassName
     * @return string
     */
    private function routingMessage($controllerClassName)
    {
        return "Trouble installing controller '$controllerClassName'.\n" .
        'Did you define the controller in the default namespace?';
    }
}