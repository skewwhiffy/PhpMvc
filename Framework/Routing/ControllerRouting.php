<?php namespace Framework\Routing;

use Framework\Common\PathExtensions;
use Framework\Exceptions\ControllerRoutingException;
use Framework\ViewRendering\IFileReader;
use ReflectionClass;
use ReflectionException;

/**
 * Class ControllerRouting
 * @package Framework\Routing
 */
class ControllerRouting
{
    private $request;
    private $paths;
    private $controllers;

    /**
     * @param IRequest $request
     * @param IFileReader $controllers
     */
    public function __construct(
        IFileReader $controllers,
        IRequest $request = null)
    {
        if ($request === null)
        {
            $request = new Request();
        }
        $this->request = $request;
        $this->paths = new PathExtensions();
        $this->controllers = $controllers;
    }

    /**
     * @return IRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function controllerName()
    {
        return $this->paths->splitPath($this->request->getUri())[0];
    }

    /**
     * @return string
     */
    public function controllerClassName()
    {
        return $this->controllerName() . 'Controller';
    }

    /**
     * @return string
     */
    public function actionName()
    {
        return $this->paths->splitPath($this->request->getUri())[1];
    }

    /**
     * @return bool
     * @throws ControllerRoutingException
     */
    public function shouldInvoke()
    {
        $controllerClassName = $this->controllerName() . 'Controller';
        $controllerFilename = $this->cleanName("$controllerClassName.php");
        $controllerFound = false;
        foreach ($this->controllers->getFiles() as $filename)
        {
            if ($this->cleanName($filename) === $controllerFilename)
            {
                $controllerFound = true;
                break;
            }
        }
        if (!$controllerFound)
        {
            return false;
        }
        $this->controllers->includeFile($controllerFilename);
        try
        {
            $class = new ReflectionClass($controllerClassName);
        }
        catch (ReflectionException $ex)
        {
            throw new ControllerRoutingException($controllerClassName);
        }
        $methods = $class->getMethods();
        $actionName = $this->cleanName($this->actionName());
        foreach ($methods as $method)
        {
            if ($this->cleanName($method->name) === $actionName)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $name
     *
     * @return string
     */
    private function cleanName($name)
    {
        return trim(strtolower($name));
    }
}