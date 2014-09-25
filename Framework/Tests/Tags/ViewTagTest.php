<?php

namespace Framework\Tests\Tags;

require_once '/Tags/ViewTag.php';
require_once '/Exceptions/TagWithNoContentException.php';

use Framework\Tags\ViewTag;
use Framework\Exceptions\TagWithNoContentException;

/**
 * Class ViewTagTest
 * @package Framework\Tests\Tags
 */
class ViewTagTest extends \PHPUnit_Framework_TestCase
{
    private $openTag = '<@';
    private $closeTag = '@@>';

    public function testFirstTagExtractedCorrectly()
    {
        $key = 'key';
        $value = 'value';
        $contents = "$key=$value";

        $tag = $this->constructViewTag("$this->openTag$contents$this->closeTag");

        $this->assertEquals($contents, $tag->getContents());
        $this->assertEquals($key, $tag->getKey());
        $this->assertEquals($value, $tag->getValue());
    }

    public function testKeyAndValueTrimmed(){
        $key = 'key';
        $value = 'value';
        $contents = " $key  =   $value    ";

        $tag = $this->constructViewTag("$this->openTag$contents$this->closeTag");

        $this->assertEquals($contents, $tag->getContents());
        $this->assertEquals($key, $tag->getKey());
        $this->assertEquals($value, $tag->getValue());
        $this->assertEquals('', $tag->getRemainderCode());
    }

    public function testValueNull() {
        $key = 'key';

        $tag = $this->constructViewTag("$this->openTag $key  $this->closeTag");

        $this->assertEquals($key, $tag->getKey());
        $this->assertNull($tag->getValue());
    }

    public function testEmptyThrows() {
        $this->setExpectedException(get_class(new TagWithNoContentException()));

        $this->constructViewTag("$this->openTag  $this->closeTag");
    }

    public function testRemainderCodeCorrect(){
        $key = 'key';
        $value = 'value';
        $contents = " $key  =   $value    ";
        $remainder = 'This is the remainder of the code';
        $code = "before text $this->openTag$contents$this->closeTag$remainder";

        $tag = $this->constructViewTag($code);

        $this->assertEquals($tag->getRemainderCode(), $remainder);
    }

    public function testGetTagsWorks() {
        $open = $this->openTag;
        $close = $this->closeTag;
        $keys = array('key1', 'key2', 'key3', 'key4', 'key5', 'key6');
        $values = array('value1', 'value2', null, 'value4', null, 'value6');
        $spaces = 0;
        $contents = array();
        for($i = 0; $i < sizeof($keys); $i++){
            $key = $keys[$i];
            $value = $values[$i];
            $preSpaces = str_repeat(' ', $spaces++);
            $preEqualsSpaces = str_repeat(' ', $spaces++);
            $postEqualsSpaces = str_repeat(' ', $spaces++);
            $postSpaces = str_repeat(' ', $spaces++);
            $content = "$open$preSpaces$key";
            if (!is_null($value)) {
                $content .= "$preEqualsSpaces=$postEqualsSpaces$value";
            }
            $content .= "$postSpaces$close";
            $contents[] = $content;
        }
        $count = 1;
        $code = '';
        for($i = 0; $i < sizeof($contents); $i++){
            $code .= str_repeat(' ', $count++);
            $code .= "pre$i";
            $code .= str_repeat(' ', $count++);
            $code .= $contents[$i];
        }
        $code .= str_repeat(' ', $count++);
        $code .= 'end';
        $code .= str_repeat(' ', $count);

        $tags = ViewTag::getTags($open, $close, $code);

        $this->assertEquals(sizeof($contents), sizeof($tags));
    }

    /**
     * @param string $code
     * @param int $startIndex
     * @return ViewTag
     */
    private function constructViewTag($code, $startIndex = 0)
    {
        return new ViewTag($this->openTag, $this->closeTag, $code, $startIndex);
    }
}
 