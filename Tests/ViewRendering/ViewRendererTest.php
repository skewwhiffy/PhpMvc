<?php
require_once __DIR__ . '/../Includes.php';

use Framework\ViewRendering\ViewRenderer;
use Framework\FileIo\IFileReader;

/**
 * Class ViewRendererTest
 */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class ViewRendererTest extends PHPUnit_Framework_TestCase
{
    /** @var PHPUnit_Framework_MockObject_MockObject | IFileReader */
    private $fileReader;

    /** @var array */
    private $views;

    /** @var TagFactory */
    private $tags;

    public function setUp()
    {
        $this->fileReader = $this->getMock('Framework\FileIo\IFileReader');
        $this->views = [];
        $this->tags = new TagFactory();
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
        $code = 'This just works ' . $this->tags->expression('$model') . ' times';
        $this->addView($viewName, $code);
        $renderer = $this->getRenderer();

        $result = $renderer->render($viewName, 50);

        $this->assertThat($result, $this->equalTo('This just works 50 times'));
    }

    public function testTemplaterWorks()
    {
        $viewName = 'view';
        $templateViewName = 'templateView';
        $code = $this->tags->template($templateViewName)
            . $this->tags->content('contentMarker')
            . 'This is the content'
            . $this->tags->endContent();
        $templateCode = 'This is before the container'
            . $this->tags->container('contentMarker')
            . 'This is after the container';
        $this->addView($viewName, $code);
        $this->addView($templateViewName, $templateCode);
        $renderer = $this->getRenderer();

        $result = $renderer->render($viewName, 50);

        $this->assertThat($result, $this->equalTo(
            'This is before the container'
            . 'This is the content'
            . 'This is after the container'));
    }

    public function testTemplaterWorksWithModels()
    {
        $viewName = 'view';
        $templateViewName = 'templateView';
        $code = $this->tags->template($templateViewName)
            . $this->tags->templateModel('$model + 1')
            . $this->tags->content('contentMarker')
            . $this->tags->expression('$model')
            . ' is the value of the model in the view'
            . $this->tags->endContent();
        $templateCode = $this->tags->expression('$model')
            . ' is the value of the model in the template'
            . $this->tags->container('contentMarker');
        $this->addView($viewName, $code);
        $this->addView($templateViewName, $templateCode);
        $renderer = $this->getRenderer();

        $result = $renderer->render($viewName, 50);

        $this->assertThat($result, $this->equalTo(
            '51 is the value of the model in the template'
            .'50 is the value of the model in the view'
        ));
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