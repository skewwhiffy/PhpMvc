<?php namespace Framework\BaseClasses;

use Framework\Common\StringExtensions;
use Framework\Exceptions\NotImplementedException;
use Framework\FileIo\FileReader;
use Framework\FileIo\IFileReader;

/**
 * Class HtmlController
 * @package Framework\BaseClasses
 */
abstract class HtmlController
{
    /** @var StringExtensions */
    private $strings;

    /** @var IFileReader */
    private $views;

    /** @var string */
    private $controllerName;

    /**
     * @param $model
     * @param string $viewName
     */
    protected function view($model = null, $viewName = null)
    {

    }

    /**
     * @return IFileReader
     */
    protected function getViews()
    {
        if ($this->views === null)
        {
            $this->views = new FileReader(__DIR__ . '\..\..\Site\Views');
        }
        return $this->views;
    }

    /**
     * @return string
     */
    protected function getControllerName()
    {
        if ($this->controllerName === null)
        {
            $className = get_class($this);
            if ($this->getStringExtensions()->endsWith($className, 'controller'))
            {
                $className = $this->getStringExtensions()->removeSuffix('controller');
            }
            $this->controllerName = $className;
        }
        return $this->controllerName;
    }

    /**
     * @return StringExtensions
     */
    private function getStringExtensions()
    {
        if ($this->strings === null)
        {
            $this->strings = new StringExtensions();
        }
        return $this->strings;
    }
}