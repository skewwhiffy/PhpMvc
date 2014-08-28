<?php
require_once 'FilePathHelper.php';

class CssController extends ControllerBase {

  private $fileReader;

  private $filePathHelper;

  public function __construct() {
    parent::__construct();
    $this->fileReader = new FileReader();
    $this->filePathHelper = new FilePathHelper();
  }

  public function Index($args) {
    $cssName = $this->filePathHelper->JoinPaths($args);
    $fileName = $this->filePathHelper->JoinPaths($this->fileReader->pathToCss, $cssName . '.css');
    header('Content-type:text/css');
    readfile($fileName);
  }
}
