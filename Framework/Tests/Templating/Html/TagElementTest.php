<?php
namespace Framework\Tests\Templating\Html;

require_once '/Templating/Html/TagElement.php';

use Framework\Templating\Html\TagElement;
use Framework\Templating\Tags\ViewTag;

/**
 * Class TagElementTest
 * @package Framework\Tests\Templating\Html;
 */
class TagElementTest extends \PHPUnit_Framework_TestCase {

    private $openTag = ViewTag::OPEN_TAG;

    private $closeTag = ViewTag::CLOSE_TAG;

    public function testPersistsTag() {
        $tag = new ViewTag("$this->openTag code $this->closeTag");
        $element = new TagElement($tag);
        $this->assertSame($tag, $element->getTag());
    }
}