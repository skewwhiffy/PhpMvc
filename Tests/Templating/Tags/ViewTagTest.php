<?php
require_once __DIR__ . '/../../Includes.php';

use Framework\Constants\Constants;
use Framework\Exceptions\OpenTagNotClosedException;
use Framework\Templating\Tags\TagExtractor;
use Framework\Templating\Tags\ViewTag;
use Framework\Exceptions\TagWithNoContentException;

/**
 * Class ViewTagTest
 * @package Framework\Tests\Tags
 */
class ViewTagTest extends PHPUnit_Framework_TestCase
{
    private $openTag = Constants::openTag;
    private $closeTag = Constants::closeTag;
    /** @var TagFactory */
    private $tags;

    public function setUp()
    {
        $this->tags = new TagFactory();
    }

    public function testSingleTagExtractedCorrectly()
    {
        $key = 'key';
        $value = 'value';
        $contents = "$key=$value";

        $tag = $this->constructViewTag($this->tags->inTags($contents));

        $this->assertEquals($contents, $tag->getContents());
        $this->assertEquals($key, $tag->getKey());
        $this->assertEquals($value, $tag->getValue());
    }

    public function testCodeBeforeExtractedCorrectly()
    {
        $key = 'key';
        $value = 'value';
        $contents = $this->tags->inTags("$key = $value");
        $codeBefore = 'This is the code before, yes it is';

        $tag = $this->constructViewTag("$codeBefore$contents");

        $this->assertThat($tag->getCodeBefore(), $this->equalTo($codeBefore));
    }

    public function testFirstOfTwoTagsExtractedCorrectly()
    {
        $firstTagKey = 'hello';
        $remainder = $this->tags->inTags('hello2');
        $tag = $this->constructViewTag($this->tags->inTags($firstTagKey) . $remainder);

        $this->assertEquals($firstTagKey, $tag->getKey());
        $this->assertEquals($remainder, $tag->getCodeAfter());
    }

    public function testKeyAndValueTrimmed()
    {
        $key = 'key';
        $value = 'value';
        $contents = " $key  =   $value    ";

        $tag = $this->constructViewTag($this->tags->inTags($contents));

        $this->assertEquals($contents, $tag->getContents());
        $this->assertEquals($key, $tag->getKey());
        $this->assertEquals($value, $tag->getValue());
        $this->assertEquals('', $tag->getCodeAfter());
    }

    public function testValueNull()
    {
        $key = 'key';

        $tag = $this->constructViewTag($this->tags->inTags($key));

        $this->assertEquals($key, $tag->getKey());
        $this->assertNull($tag->getValue());
    }

    public function testKeyNull()
    {
        $tag = $this->constructViewTag('<h1>3 + 4 is ' . $this->tags->inTags('=3+4') . '</h1>');

        $this->assertThat($tag->getValue(), $this->equalTo('3+4'));;
        $this->assertThat($tag->getKey(), $this->isEmpty());
    }

    public function testEmptyThrows()
    {
        $this->setExpectedException(get_class(new TagWithNoContentException(null)));

        $this->constructViewTag($this->tags->inTags('  '));
    }

    public function testNonMatchingOpenTagThrows()
    {
        $this->setExpectedException(get_class(new OpenTagNotClosedException(null)));

        $tag = $this->constructViewTag("$this->openTag blah blah blah");

        var_dump($tag);
    }

    public function testRemainderCodeCorrect()
    {
        $key = 'key';
        $value = 'value';
        $contents = " $key  =   $value    ";
        $remainder = 'This is the remainder of the code';
        $code = 'before text ' . $this->tags->inTags($contents) .$remainder;

        $tag = $this->constructViewTag($code);

        $this->assertEquals($tag->getCodeAfter(), $remainder);
    }

    public function testGetTagsWorks()
    {
        $open = $this->openTag;
        $close = $this->closeTag;
        $keys = ['key1', 'key2', 'key3', 'key4', 'key5', 'key6'];
        $values = ['value1', 'value2', null, 'value4', null, 'value6'];
        $spaces = 0;
        $contents = [];
        for ($i = 0; $i < sizeof($keys); $i++)
        {
            $key = $keys[$i];
            $value = $values[$i];
            $preSpaces = str_repeat(' ', $spaces++);
            $preEqualsSpaces = str_repeat(' ', $spaces++);
            $postEqualsSpaces = str_repeat(' ', $spaces++);
            $postSpaces = str_repeat(' ', $spaces++);
            $content = "$open$preSpaces$key";
            if (!is_null($value))
            {
                $content .= "$preEqualsSpaces=$postEqualsSpaces$value";
            }
            $content .= "$postSpaces$close";
            $contents[] = $content;
        }
        $count = 1;
        $code = '';
        for ($i = 0; $i < sizeof($contents); $i++)
        {
            $code .= str_repeat(' ', $count++);
            $code .= "pre$i";
            $code .= str_repeat(' ', $count++);
            $code .= $contents[$i];
        }
        $code .= str_repeat(' ', $count++);
        $code .= 'end';
        $code .= str_repeat(' ', $count);

        $tagExtractor = new TagExtractor();
        $tags = $tagExtractor->getTags($code);

        $this->assertEquals(sizeof($contents), sizeof($tags));
        for ($i = 0; $i < sizeof($tags); $i++)
        {
            $this->assertEquals($keys[$i], $tags[$i]->getKey());
            $this->assertEquals($values[$i], $tags[$i]->getValue());
        }
    }

    public function testGetTagCodeWorks()
    {
        $code = $this->tags->inTags(' key = value ');
        $mixedIn = "before, before<h1 />$code<div/>after after";

        $result = $this->constructViewTag($mixedIn)->getTagCode();

        $this->assertThat($result, $this->equalTo($code));
    }

    /**
     * @param string $code
     *
     * @return ViewTag
     */
    private function constructViewTag($code)
    {
        return new ViewTag($code);
    }
}
 