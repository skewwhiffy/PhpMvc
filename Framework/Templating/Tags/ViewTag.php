<?php
namespace Framework\Templating\Tags;

require_once '/Exceptions/TagWithNoContentException.php';
require_once 'IViewTag.php';

use Framework\Exceptions\TagWithNoContentException;

/**
 * Class ViewTag
 * @package Framework\Templating\Tags
 */
class ViewTag implements IViewTag
{
    /** @var string */
    const OPEN_TAG = '<@';

    const CLOSE_TAG = '@@>';

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
    private $codeBefore;

    /** @var string */
    private $codeAfter;

    /**
     * @param string $code
     * @throws TagWithNoContentException
     */
    public function __construct($code)
    {
        $this->code = $code;
        if (trim($this->getContents()) === '') {
            throw new TagWithNoContentException();
        }
    }

    /**
     * @param string $code
     * @return ViewTag[]
     */
    public static function getTags($code)
    {
        $tags = [];
        $remainingCode = $code;
        while (strpos($remainingCode, self::OPEN_TAG) !== false) {
            $newTag = new ViewTag($remainingCode);
            $tags[] = $newTag;
            $remainingCode = $newTag->getCodeAfter();
        }
        return $tags;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        if (!is_null($this->contents)) {
            return $this->contents;
        }
        $this->startIndex = strpos($this->code, self::OPEN_TAG);
        $tagContentsStartIndex = $this->startIndex + strlen(self::OPEN_TAG);
        $tagContentsEndIndex = $this->getEndIndex();
        $contentsLength = $tagContentsEndIndex - $tagContentsStartIndex;
        $this->contents = substr($this->code, $tagContentsStartIndex, $contentsLength);
        return $this->contents;
    }

    /**
     * @return int
     */
    private function getStartIndex()
    {
        if (is_null($this->startIndex)) {
            $this->startIndex = strrpos($this->code, self::OPEN_TAG);
        }
        return $this->startIndex;
    }

    /**
     * @return int
     */
    private function getEndIndex()
    {
        if (is_null($this->endIndex)) {
            $this->endIndex = strpos($this->code, self::CLOSE_TAG, $this->getStartIndex());
        }
        return $this->endIndex;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        $this->populateKeyAndValue();
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        $this->populateKeyAndValue();
        return $this->value;
    }

    /**
     * @return string
     */
    public function getCodeBefore(){
        if (is_null($this->codeBefore)) {
            $this->codeBefore = substr($this->code, 0, $this->getStartIndex());
        }
        return $this->codeBefore;
    }

    /**
     * @return string
     */
    public function getCodeAfter()
    {
        if (is_null($this->codeAfter)) {
            $this->codeAfter = substr($this->code, $this->getRemainderIndex());
        }
        return $this->codeAfter;
    }

    /**
     * @return int
     */
    private function getRemainderIndex()
    {
        if (is_null($this->remainderIndex)) {
            $this->remainderIndex = $this->getEndIndex() + strlen(self::CLOSE_TAG);
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