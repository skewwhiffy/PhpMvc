<?php

require_once __DIR__ . '/../../Framework/Includes.php';

use Framework\ViewRendering\PhpRenderer;

/**
 * Class PhpRendererTest
 */
class PhpRendererTest extends PHPUnit_Framework_TestCase
{
    /** @var  PhpRenderer */
    private $renderer;

    public function setUp()
    {
        $this->renderer = new PhpRenderer();
    }

    public function testPlainHtmlRenders()
    {
        $code = '<h1>Hello</h1><p>There is nothing special here</p>';

        $result = $this->renderer->renderToHtml($code);

        $this->assertThat($result, $this->equalTo($code));
    }

    public function testSimplePhpRenders()
    {
        $code = '<?php echo 3 + 4; ?>';

        $result = $this->renderer->renderToHtml($code);

        $this->assertThat($result, $this->equalTo('7'));
    }

    public function testVariablesGetUsed()
    {
        $model = 'hello world';
        $code = '<?php echo $model; ?>';
        $this->renderer->addVariable('model', $model);

        $result = $this->renderer->renderToHtml($code);

        $this->assertThat($result, $this->equalTo($model));
    }
}