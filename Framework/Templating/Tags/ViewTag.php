<?php
namespace Framework\Templating\Tags;

use Framework\Constants\Constants;
use Framework\Exceptions\OpenTagNotClosedException;
use Framework\Exceptions\TagWithNoContentException;

/**
 * Class ViewTag
 * @package Framework\Templating\Tags
 */
class ViewTag implements IViewTag
{
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
     *
     * @throws TagWithNoContentException
     */
    public function __construct($code)
    {
        $this->code = $code;
        if (trim($this->getContents()) === '')
        {
            throw new TagWithNoContentException($code);
        }
    }

    /**
     * @return string
     */
    public function getContents()
    {
        if (!is_null($this->contents))
        {
            return $this->contents;
        }
        $tagContentsStartIndex = $this->getStartIndex() + strlen(Constants::openTag);
        $tagContentsEndIndex = $this->getEndIndex();
        $contentsLength = $tagContentsEndIndex - $tagContentsStartIndex;
        $this->contents = substr($this->code, $tagContentsStartIndex, $contentsLength);
        return $this->contents;
    }

    /**
     * @return string
     */
    public function getTagCode()
    {
        return Constants::openTag . $this->getContents() . Constants::closeTag;
    }

    /**
     * @return int
     */
    private function getStartIndex()
    {
        if (is_null($this->startIndex))
        {
            $this->startIndex = strpos($this->code, Constants::openTag);
        }
        return $this->startIndex;
    }

    /**
     * @return int
     */
    private function getEndIndex()
    {
        if (is_null($this->endIndex))
        {
            $this->endIndex = strpos($this->code, Constants::closeTag, $this->getStartIndex());
            if ($this->endIndex === false)
            {
                throw new OpenTagNotClosedException($this->code);
            }
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
    public function getCodeBefore()
    {
        if (is_null($this->codeBefore))
        {
            $this->codeBefore = substr($this->code, 0, $this->getStartIndex());
        }
        return $this->codeBefore;
    }

    /**
     * @return string
     */
    public function getCodeAfter()
    {
        if (is_null($this->codeAfter))
        {
            $this->codeAfter = substr($this->code, $this->getRemainderIndex());
        }
        return $this->codeAfter;
    }

    /**
     * @return int
     */
    private function getRemainderIndex()
    {
        if (is_null($this->remainderIndex))
        {
            $this->remainderIndex = $this->getEndIndex() + strlen(Constants::closeTag);
        }
        return $this->remainderIndex;
    }

    private function populateKeyAndValue()
    {
        if (!is_null($this->key))
        {
            return;
        }
        $contents = $this->getContents();
        $split = explode('=', $contents, 3);
        if (sizeof($split) > 0)
        {
            $this->key = trim($split[0]);
        }
        if (sizeof($split) > 1)
        {
            $this->value = trim($split[1]);
        }
    }
}