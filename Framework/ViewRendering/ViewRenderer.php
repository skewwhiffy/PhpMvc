<?php
namespace Framework\ViewRendering;

use Framework\Templating\Html\Document;

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
     * @throws \ErrorException
     * @return string
     */
    public function render($viewName, $model = null)
    {
        $code = $this->viewFileReader->readFile($viewName);
        $document = new Document($code);
        while ($document->hasTemplate())
        {
            $templateCode = $this->viewFileReader->readFile($document->templateName());
            $templateDocument = new Document($templateCode);
            $templateDocument->addContent($document);
            $document = $templateDocument;
        }
        $phpCode = $document->render();
        $this->phpRenderer->addVariable('model', $model);
        return $this->phpRenderer->renderToHtml($phpCode);
    }
}