<?php namespace Framework\Routing;

use Framework\ViewRendering\IFileReader;
use ReflectionClass;
use ReflectionMethod;

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
    public function getInstance($className, $arguments)
    {
        $this->controllers->includeFile("$className.php");
        $reflection = new ReflectionClass($className);
        return $reflection->newInstanceArgs($arguments);
    }

    /**
     * @param $instance
     * @param $methodName
     * @param $arguments
     *
     * @return mixed
     */
    public function invokeMethodOnInstance($instance, $methodName, $arguments)
    {
        $reflection = new ReflectionMethod(get_class($instance), $methodName);
        return $reflection->invokeArgs($instance, $arguments);
    }
}