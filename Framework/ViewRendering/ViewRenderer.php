<?php
namespace Framework\ViewRendering;

/**
 * Class ViewRenderer
 */
class ViewRenderer
{
    private $viewFileReader;

    /**
     * @param IFileReader $viewFileReader
     */
    public function __construct($viewFileReader)
    {
        $this->viewFileReader = $viewFileReader;
    }

    /**
     * @param string $viewName
     *
     * @return string
     */
    public function render($viewName)
    {
        return $this->viewFileReader->readFile($viewName);;
    }
}