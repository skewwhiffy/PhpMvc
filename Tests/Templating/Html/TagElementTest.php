<?php
require_once __DIR__ . '/../../Includes.php';

use Framework\Constants\Constants;
use Framework\Templating\Html\TagElement;
use Framework\Templating\Tags\ViewTag;

/**
 * Class TagElementTest
 * @package Framework\Tests\Templating\Html;
 */
class TagElementTest extends PHPUnit_Framework_TestCase
{
    public function testPersistsTag()
    {
        $tag = new ViewTag(Constants::OPEN_TAG . ' code ' . Constants::CLOSE_TAG);
        $element = new TagElement($tag);
        $this->assertSame($tag, $element->getTag());
    }
}