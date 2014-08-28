<?php

class ViewTag {

  private $openTag;

  private $closeTag;

  public $startIndex;

  public $endIndex;

  public $raw;

  public $key;

  public $value;

  public function __construct($openTag, $closeTag, $startIndex, $code) {
    $this->openTag = $openTag;
    $this->closeTag = $closeTag;
    $this->startIndex = $startIndex;
    $this->endIndex = $this->GetCloseTagPosition($code);
    $this->raw = substr($code, $startIndex, $this->endIndex - $startIndex);
    $openTagLength = strlen($this->openTag);
    $closeTagLength = strlen($this->closeTag);
    $contentsLength = strlen($this->raw) - $openTagLength - $closeTagLength;
    $contents = substr($this->raw, $openTagLength, $contentsLength);
    $contentsSplitCleaned = $this->SplitAndClean($contents);
    $this->key = $contentsSplitCleaned [0];
    $this->value = $this->GetValue($contentsSplitCleaned);
  }

  private function SplitAndClean($source) {
    $split = explode('=', $source);
    $cleaned = array ();
    foreach ( $split as $item ) {
      $cleanItem = str_replace(' ', '', $item);
      if ($cleanItem !== '') {
        $cleaned [] = $cleanItem;
      }
    }
    return $cleaned;
  }

  private function GetValue($splitString) {
    switch (count($splitString)) {
      case 1 :
        return '';
      case 2 :
        return $splitString [1];
      default :
        throw new RuntimeException("Don't understand - there are too many equals");
    }
  }

  private function GetCloseTagPosition($code) {
    $startPosition = $this->startIndex + strlen($this->openTag);
    $position = strpos($code, $this->closeTag, $startPosition);
    if ($position === false) {
      throw new RuntimeException("Can't find tag $tag");
    }
    return $position + strlen($this->closeTag);
  }

  public function CalculateContentLength() {
    return $this->endIndex - $this->startIndex - strlen($this->openTag) - strlen($this->closeTag);
  }
}
