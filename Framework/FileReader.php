<?php

require_once 'ImageDetails.php';
require_once 'FilePathHelper.php';

class FileReader {

  private $filePathHelper;
  public $pathToViews;
  public $pathToModels;
  public $pathToImages;
  public $pathToCss;

  public function __construct() {
    $this->filePathHelper = new FilePathHelper();
    $projectRoot = '../';
    $this->pathToViews = $this->filePathHelper->JoinPaths($projectRoot, 'View');
    $this->pathToModels = $this->filePathHelper->JoinPaths($projectRoot, 'Model');
    $this->pathToImages = $this->filePathHelper->JoinPaths($projectRoot, 'Image');
    $this->pathToCss = $this->filePathHelper->JoinPaths($projectRoot, 'Css');
  }

  public function GetModelPath($modelName) {
    return $this->filePathHelper->JoinPaths($this->pathToModels, $modelName . 'Model.php');
  }

  public function GetViewPath($viewName) {
    return $this->filePathHelper->JoinPaths($this->pathToViews, $viewName . '.php');
  }

  public function GetViewCode($viewName) {
    return file_get_contents($this->GetViewPath($viewName));
  }

}
