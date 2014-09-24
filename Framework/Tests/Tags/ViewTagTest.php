<?php

namespace Framework\Tests\Tags;

require_once 'Tags/ViewTag.php';

use Framework\Tags\ViewTag;

class ViewTagTest extends \PHPUnit_Framework_TestCase
{
    private $openTag = '<@';
    private $closeTag = '@@>';

    public function testFirstTagExtractedCorrectly()
    {
        $key = 'key';
        $value = 'value';
        $tag = $this->constructViewTag("$this->openTag$key=$value$this->closeTag");
        $this->assertEquals("$key=$value", $tag->getContents());
        $this->assertEquals($key, $tag->getKey());
    }

    private function constructViewTag($code, $startIndex = 0)
    {
        return new ViewTag($this->openTag, $this->closeTag, $code, $startIndex);
    }
}
 