<?php
namespace Framework\Tags;

require_once '/Exceptions/TagWithNoContentException.php';

use Framework\Exceptions\TagWithNoContentException;

/**
 * Class ViewTag
 * @package Framework\Tags
 */
class ViewTag
{
    /** @var string */
    private $openTag;

    /** @var string */
    private $code;

    /** @var string */
    private $contents;

    /** @var string */
    private $key;

    /** @var string */
    private $value;

    /** @var int */
    private $endIndex;

    /** @var int */
    private $startIndex;

    /** @var int */
    private $remainderIndex;

    /** @var string */
    private $remainderCode;

    /**
     * @param string $openTag
     * @param string $closeTag
     * @param string $code
     * @param int $startIndex
     * @throws TagWithNoContentException
     */
    public function __construct($openTag, $closeTag, $code, $startIndex = 0)
    {
        $this->openTag = $openTag;
        $this->closeTag = $closeTag;
        $this->code = $code;
        if (trim($this->getContents()) === '') {
            throw new TagWithNoContentException();
        }
    }

    /**
     * @param string $openTag
     * @param string $closeTag
     * @param string $code
     * @return ViewTag[]
     */
    public static function getTags($openTag, $closeTag, $code)
    {
        $tags = array();
        return $tags; // TODO
    }

    /** @return string */
    public function getContents()
    {
        if (!is_null($this->contents)) {
            return $this->contents;
        }
        $startIndex = strrpos($this->code, $this->openTag);
        $tagContentsStartIndex = $startIndex + strlen($this->openTag);
        $tagContentsEndIndex = $this->getEndIndex();
        $contentsLength = $tagContentsEndIndex - $tagContentsStartIndex;
        $this->contents = substr($this->code, $tagContentsStartIndex, $contentsLength);
        return $this->contents;
    }

    /** @return int */
    private function getStartIndex()
    {
        if (is_null($this->startIndex)) {
            $this->startIndex = strrpos($this->code, $this->openTag);
        }
        return $this->startIndex;
    }

    /** @return int */
    private function getEndIndex()
    {
        if (is_null($this->endIndex)) {
            $this->endIndex = strrpos($this->code, $this->closeTag, $this->getStartIndex());
        }
        return $this->endIndex;
    }

    /** @return string */
    public function getKey()
    {
        $this->populateKeyAndValue();
        return $this->key;
    }

    /** @return string */
    public function getValue()
    {
        $this->populateKeyAndValue();
        return $this->value;
    }

    /** @return string */
    public function getRemainderCode()
    {
        if (is_null($this->remainderCode)) {
            $this->remainderCode = substr($this->code, $this->getRemainderIndex());
        }
        return $this->remainderCode;
    }

    /** @return int */
    private function getRemainderIndex()
    {
        if (is_null($this->remainderIndex)) {
            $this->remainderIndex = $this->getEndIndex() + strlen($this->closeTag);
        }
        return $this->remainderIndex;
    }

    private function populateKeyAndValue()
    {
        if (!is_null($this->key)) {
            return;
        }
        $contents = $this->getContents();
        $split = explode('=', $contents, 3);
        if (sizeof($split) > 0) {
            $this->key = trim($split[0]);
        }
        if (sizeof($split) > 1) {
            $this->value = trim($split[1]);
        }
    }
}