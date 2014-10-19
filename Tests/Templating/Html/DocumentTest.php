<?php
require_once __DIR__ . '/../../Includes.php';

use Framework\Exceptions\UnrecognizedTagTypeException;
use Framework\Templating\Html\Document;
use Framework\Templating\Html\HtmlElement;
use Framework\Templating\Html\TagElement;

/**
 * Class DocumentTest
 * @package Framework\Tests\Templating\Html
 */
class DocumentTest extends PHPUnit_Framework_TestCase
{
    /** @var TagFactory */
    private $tagFactory;

    public function setUp()
    {
        $this->tagFactory = new TagFactory();
    }

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
            . $this->tagFactory->inTags('hello')
            . ' middle</h1> '
            . $this->tagFactory->inTags('goodbye = hello')
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

    public function testExpressionTagsAreRendered()
    {
        $code = '<h1>3 + 4 is ' . $this->tagFactory->expression('3 + 4') . ' </h1>';
        $document = new Document($code);

        $rendered = $document->render(null);

        $this->assertThat($rendered, $this->equalTo('<h1>3 + 4 is <?php echo 3 + 4;?> </h1>'));
    }

    public function testInvalidElementTypeThrows()
    {
        $invalidTags = ['This is not a view tag'];
        $mockExtractor = $this->getMock('Framework\Templating\Tags\ITagExtractor');
        $mockExtractor->expects($this->any())
            ->method('getElements')
            ->will($this->returnValue($invalidTags));
        $code = '<html/>';
        $document = new Document($code, $mockExtractor);
        $this->setExpectedException('Framework\Exceptions\UnrecognizedElementTypeException');

        $document->render();
    }

    public function testWhenDocumentHasNoTemplateThenHasTemplateReturnsFalse()
    {
        $code = '<h1>This is a page with not templates</h1>';
        $document = new Document($code);

        $this->assertThat($document->hasTemplate(), $this->isFalse());
        $this->assertThat($document->hasTemplate(), $this->isFalse());
    }

    public function testWhenDocumentHasTemplateThenHasTemplateReturnsTrue()
    {
        $code = $this->tagFactory->template('theTemplate')
            . $this->tagFactory->content('main')
            . '<h1>This has a template</h1>'
            . $this->tagFactory->endContent();
        $document = new Document($code);

        $this->assertThat($document->hasTemplate(), $this->isTrue());
    }

    public function testWhenDocumentHasTemplateThenRenderingThrowsException()
    {
        $this->setExpectedException(UnrecognizedTagTypeException::getClassName());
        $code = $this->tagFactory->template('theTemplate')
            . $this->tagFactory->content('main')
            . '<h1>This has a template</h1>'
            . $this->tagFactory->endContent();
        $document = new Document($code);

        $document->render();
    }

    public function testWhenDocumentHasTemplateThenGetContentsWorks()
    {
        $firstContent = '
First content
';
        $secondContent = '
Second content
';
        $code = $this->tagFactory->template('theTemplate')
            . $this->tagFactory->content('first')
            . $firstContent
            . $this->tagFactory->endContent()
            . $this->tagFactory->content('second')
            . $secondContent
            . $this->tagFactory->endContent();
        $document = new Document($code);

        $result = $document->getContent();

        $this->assertNotNull($result);
        $this->assertThat(array_key_exists('first', $result), $this->isTrue());
        $this->assertThat($result['first'], $this->equalTo($firstContent));
        $this->assertThat(array_key_exists('second', $result), $this->isTrue());
        $this->assertThat($result['second'], $this->equalTo($secondContent));
        $this->assertThat($result, $this->equalTo($document->getContent()));
    }

    public function testAddContentWorks()
    {
        $template = '<h1>'
            . $this->tagFactory->container('first')
            . '</h1><h2>'
            . $this->tagFactory->container('second')
            . '</h2>'
            . $this->tagFactory->expression('\'hello\'');
        $first = 'First';
        $second = 'Second';
        $code = $this->tagFactory->template('theTemplate')
            . $this->tagFactory->content('first')
            . $first
            . $this->tagFactory->endContent()
            . $this->tagFactory->content('second')
            . $second
            . $this->tagFactory->endContent();
        $codeDoc = new Document($code);
        $templateDoc = new Document($template);

        $templateDoc->addContent($codeDoc);
        $result = $templateDoc->render();

        $expected = "<h1>$first</h1><h2>$second</h2><?php echo 'hello';?>";
        $this->assertThat($result, $this->equalTo($expected));
    }

    public function testWhenNotTemplateIsTemplateReturnsFalse()
    {
        $nonTemplate = '<h1>Not a template</h1>';
        $document = new Document($nonTemplate);

        $this->assertThat($document->isTemplate(), $this->isFalse());
    }

    public function testWhenTemplateIsTemplateReturnsTrue()
    {
        $template = '<h1>Is a template</h1>' . $this->tagFactory->container('first');
        $document = new Document($template);

        $this->assertThat($document->isTemplate(), $this->isTrue());
    }

    /**
     * @param $element
     *
     * @return HtmlElement
     */
    private function asHtmlElement($element)
    {
        $this->assertThat($element instanceof HtmlElement, $this->isTrue());;
        return $element;
    }

    /**
     * @param mixed $element
     *
     * @return TagElement
     */
    private function asTagElement($element)
    {
        $this->assertThat($element instanceof TagElement, $this->isTrue(), gettype($element));
        return $element;
    }
}