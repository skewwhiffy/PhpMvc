<?php
require_once __DIR__ . '/../../Includes.php';

use Framework\Constants\Constants;
use Framework\Templating\Html\TagElement;
use Framework\Templating\Tags\ViewTag;

/**
 * Class TagElementTest
 * @package Framework\Tests\Templating\Html;
 */
class TagElementTest extends PHPUnit_Framework_TestCase {

    private $openTag = Constants::openTag;

    private $closeTag = Constants::closeTag;

    public function testPersistsTag() {
        $tag = new ViewTag("$this->openTag code $this->closeTag");
        $element = new TagElement($tag);
        $this->assertSame($tag, $element->getTag());
    }
}