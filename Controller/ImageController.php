<?php
require_once 'FilePathHelper.php';

class ImageController extends ControllerBase {

  private $fileReader;

  private $filePathHelper;

  public function __construct() {
    parent::__construct();
    $this->fileReader = new FileReader();
    $this->filePathHelper = new FilePathHelper();
  }

  public function Index($args) {
    $imageName = $this->filePathHelper->JoinPaths($args);
    $image = new ImageDetails($this->fileReader->pathToImages, $imageName);
    $fileName = $image->path;
    header($image->contentTypeHeader);
    readfile($fileName);
  }
}
