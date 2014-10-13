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
     * @param PhpRenderer $phpRenderer
     */
    public function __construct($viewFileReader, $phpRenderer = null)
    {
        if (empty($phpRenderer))
        {
            $phpRenderer = new PhpRenderer();
        }
        $this->viewFileReader = $viewFileReader;
        $this->phpRenderer = $phpRenderer;
    }

    /**
     * @param string $viewName
     *
     * @param mixed  $model
     *
     * @return string
     */
    public function render($viewName, $model = null)
    {
        $code = $this->viewFileReader->readFile($viewName);
        return $this->phpRenderer->renderToHtml($code);
    }
}