<?php

class ImageController extends ControllerBase {

  private $fileReader;
  
  public function __construct() {
    parent::__construct();
    $this->fileReader = new FileReader();
  }
  
  public function Index($args) {
    $elements = count($args);
    if ($elements === 1 && strcasecmp($args[0], 'favicon') === 0) {
      $image = 'favicon';
    } else {
      throw new BadMethodCallException();
    }
    $fileName = $this->fileReader->GetImagePath($image);
    header('Content-Type:image/jpeg');
    readfile($fileName);
  }

}
