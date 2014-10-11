<?php
require_once __DIR__.'/../../../Includes.php';

use Framework\Templating\Html\Document;
use Framework\Templating\Html\HtmlElement;
use Framework\Templating\Html\TagElement;
use Framework\Templating\Tags\ViewTag;

/**
 * Class DocumentTest
 * @package Framework\Tests\Templating\Html
 */
class DocumentTest extends PHPUnit_Framework_TestCase
{
    private $openTag = ViewTag::OPEN_TAG;
    private $closeTag = ViewTag::CLOSE_TAG;

    public function testHtmlCodeResultsInHtmlElement()
    {
        $code = '<html><h1>Hello</h1></html>';
        $document = new Document($code);
        $elements = $document->getElements();
        $this->assertEquals(1, sizeof($elements));

        $htmlElement = $this->asHtmlElement($elements[0]);
        $this->assertEquals($code, $htmlElement->getCode());
    }

    public function testHtmlCodeWithTagsAreSplitProperly()
    {
        $code = '<h1>before'
            . "$this->openTag hello $this->closeTag"
            . ' middle</h1> '
            . "$this->openTag goodbye = hello $this->closeTag"
            . ' end';
        $document = new Document($code);
        $elements = $document->getElements();
        $this->assertEquals(5, sizeof($elements));

        $this->assertEquals('<h1>before', $this->asHtmlElement($elements[0])->getCode());
        $helloTag = $this->asTagElement($elements[1])->getTag();
        $this->assertEquals('hello', $helloTag->getKey());
        $this->assertNull($helloTag->getValue());
        $this->assertEquals(' middle</h1> ', $this->asHtmlElement($elements[2])->getCode());
        $goodbyeTag = $this->asTagElement($elements[3])->getTag();
        $this->assertEquals('goodbye', $goodbyeTag->getKey());
        $this->assertEquals('hello', $goodbyeTag->getValue());
        $this->assertEquals(' end', $this->asHtmlElement($elements[4])->getCode());
    }

    public function testExpressionTagsAreRendered(){
        $code = '<h1>3 + 4 is <@=3+4 @@></h1>';
        $document = new Document($code);

        $rendered = $document->renderExpressionTags(null);

        $this->assertThat($rendered, $this->stringContains('echo'));
    }

    /**
     * @param $element
     * @return HtmlElement
     */
    private function asHtmlElement($element)
    {
        $this->assertInstanceOf('Framework\Templating\Html\HtmlElement', $element);
        return $element;
    }

    /**
     * @param $element
     * @return TagElement
     */
    private function asTagElement($element)
    {
        $this->assertInstanceOf('Framework\Templating\Html\TagElement', $element, gettype($element));
        return $element;
    }
}