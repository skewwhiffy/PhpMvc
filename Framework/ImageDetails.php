<?php

class ImageDetails {
  private $filePathHelper;
  
  private $pathToImages;
  
  private $imageName;
  
  public $path;
  
  public $contentTypeHeader;
  
  // So far, this only supports jpg files
  // TODO: Support other types
  public function __construct($pathToImages, $imageName) {
    $this->filePathHelper = new FilePathHelper();
    $this->pathToImages = $pathToImages;
    $this->imageName = $imageName;
    $this->path = $this->filePathHelper->JoinPaths($pathToImages, $imageName . '.jpg');
    $this->contentTypeHeader = 'Content-Type:image/jpeg';
  }
}