<?php

namespace Framework\Tags;

class ViewTag
{
    private $openTag;
    private $code;
    private $contents;

    public function __construct($openTag, $closeTag, $code, $startIndex)
    {
        $this->openTag = $openTag;
        $this->closeTag = $closeTag;
        $this->code = $code;
    }

    public function getContents()
    {
        if ($this->contents) {
            return $this->contents;
        }
        $startIndex = strrpos($this->code, $this->openTag);
        $tagContentsStartIndex = $startIndex + strlen($this->openTag);
        $endIndex = strrpos($this->code, $this->closeTag, $startIndex);
        $tagContentsEndIndex = $endIndex;
        $contentsLength = $tagContentsEndIndex - $tagContentsStartIndex;
        $this->contents = substr($this->code, $tagContentsStartIndex, $contentsLength);
        return $this->contents;
    }

    public function getKey()
    {
        $contents = $this->getContents();
        $split = explode('=', $contents, 3);
        return $split[0];
    }
}