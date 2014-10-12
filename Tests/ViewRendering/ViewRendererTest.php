<?php

require_once __DIR__ . '/../../Framework/Includes.php';

use Framework\ViewRendering\ViewRenderer;
use Framework\ViewRendering\IFileReader;

/**
 * Class ViewRendererTest
 */
class ViewRendererTest extends PHPUnit_Framework_TestCase
{
    /** @var ViewRenderer */
    private $viewRenderer;

    /** @var MockFileReader */
    private $fileReader;

    public function setUp()
    {
        $this->fileReader = new MockFileReader();
        $this->viewRenderer = new ViewRenderer($this->fileReader);
    }

    public function testPlainHtmlViewWorks()
    {
        $viewName = 'view';
        $code = '<h1>This just works</h1>';
        $this->fileReader->addView($viewName, $code);

        $result = $this->viewRenderer->render($viewName);

        $this->assertThat($result, $this->equalTo($code));
    }
}

/**
 * Class MockFileReader
 */
class MockFileReader implements IFileReader
{
    /** @var array */
    private $views = [];

    /**
     * @param string $viewName
     * @param string $code
     */
    public function addView($viewName, $code)
    {
        $this->views[$viewName] = $code;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    function readFile($path)
    {
        return $this->views[$path];
    }
}