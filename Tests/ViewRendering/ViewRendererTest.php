<?php

require_once __DIR__ . '/../../Framework/Includes.php';

use Framework\ViewRendering\ViewRenderer;
use Framework\ViewRendering\IFileReader;

/**
 * Class ViewRendererTest
 */
class ViewRendererTest extends PHPUnit_Framework_TestCase
{
    /** @var PHPUnit_Framework_MockObject_MockObject | IFileReader */
    private $fileReader;

    /** @var array */
    private $views;

    public function setUp()
    {
        $this->fileReader = $this->getMock('Framework\ViewRendering\IFileReader');
        $this->views = [];
    }

    public function testPlainHtmlViewWorks()
    {
        $viewName = 'view';
        $code = '<h1>This just works</h1>';
        $this->addView($viewName, $code);
        $renderer = $this->getRenderer();

        $result = $renderer->render($viewName);

        $this->assertThat($result, $this->equalTo($code));
    }

    public function testStaticPhpWorks()
    {
        $viewName = 'view';
        $code = 'This just works <?php echo 7;?> times';
        $this->addView($viewName, $code);
        $renderer = $this->getRenderer();

        $result = $renderer->render($viewName);

        $this->assertThat($result, $this->equalTo('This just works 7 times'));
    }

    public function testModelWorks()
    {
        $viewName = 'view';
        $code = 'This just works <?php echo $model; ?> times';
        $this->addView($viewName, $code);
        $renderer = $this->getRenderer();

        $result = $renderer->render($viewName, 50);

        $this->assertThat($result, $this->equalTo('This just works 50 times'));
    }

    public function testExpressionTagsWork()
    {
        $viewName = 'view';
        $code = 'This just works <@= $model @@> times';
        $this->addView($viewName, $code);
        $renderer = $this->getRenderer();

        $result = $renderer->render($viewName, 50);

        $this->assertThat($result, $this->equalTo('This just works 50 times'));
    }

    public function testTemplaterWorks(){
        $viewName = 'view';
        $code =
'<@template = templateView@@>
<@content=contentMarker@@>
This is the content
<@endContent@@>';
        $templateViewName = 'templateView';
        $templateCode =
'This is before the container
<@container=contentMarker@@>
This is after the container';
        $this->addView($viewName, $code);
        $this->addView($templateViewName, $templateCode);
        $renderer = $this->getRenderer();

        $result = $renderer->render($viewName, 50);

        $this->assertThat($result, $this->equalTo(
'This is before the container
This is the content
This is after the container'));;
    }

    /**
     * @return \Framework\ViewRendering\ViewRenderer
     */
    private function getRenderer()
    {
        $this->fileReader
            ->expects($this->any())
            ->method('readFile')
            ->will($this->returnValueMap($this->views));
        return new ViewRenderer($this->fileReader);
    }

    /**
     * @param string $viewName
     * @param string $code
     */
    private function addView($viewName, $code)
    {
        $this->views[] = [$viewName, $code];
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