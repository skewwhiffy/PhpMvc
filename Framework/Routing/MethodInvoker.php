<?php namespace Framework\Routing;

use Framework\Exceptions\InvalidRequestMethodException;
use Framework\FileIo\IFileReader;
use ReflectionClass;

/**
 * Class MethodInvoker
 * @package Framework\Routing
 */
class MethodInvoker
{
    /**
     * @var IFileReader
     */
    private $controllers;

    /**
     * @param IFileReader $controllers
     */
    public function __construct($controllers)
    {
        $this->controllers = $controllers;
    }

    /**
     * @param string $className
     * @param mixed $arguments
     *
     * @return mixed
     */
    public function getInstance($className, $arguments = [])
    {
        $this->controllers->includeFile("$className.php");
        $reflection = new ReflectionClass($className);
        return $reflection->newInstanceArgs($arguments);
    }

    /**
     * @param $instance
     * @param $methodName
     * @param $arguments
     * @return mixed
     * @throws InvalidRequestMethodException
     */
    public function invokeMethodOnInstance($instance, $methodName, $arguments)
    {
        $reflection = new ReflectionClass(get_class($instance));
        $catchAllMethod = null;
        foreach ($reflection->getMethods() as $method)
        {
            if ($method->getName() === $methodName)
            {
                return $method->invokeArgs($instance, $arguments);
            }
            if ($method->getName() === '__catchAll')
            {
                $catchAllMethod = $method;
            }
        }
        if ($catchAllMethod !== null)
        {
            return $catchAllMethod->invokeArgs($instance, [$methodName, $arguments]);
        }
        throw new InvalidRequestMethodException($methodName);
    }
}