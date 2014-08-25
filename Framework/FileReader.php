<?php

class FileReader {
  public $pathToViews;
  
  public $pathToModels;
  
  public $pathToImages;
  
  public function __construct() {
    $projectRoot = '../';
    $this->pathToViews = $this->JoinPaths($projectRoot, 'View');
    $this->pathToModels = $this->JoinPaths($projectRoot, 'Model');
    $this->pathToImages = $this->JoinPaths($projectRoot, 'Image');
  }
  
  public function GetModelPath($modelName) {
    return $this->JoinPaths($this->pathToModels, $modelName . 'Model.php');
  }
  
  public function GetViewPath($viewName) {
    return $this->JoinPaths($this->pathToViews, $viewName . '.php');
  }
  
  public function GetViewCode($viewName) {
    return file_get_contents($this->GetViewPath($viewName));
  }
  
  public function GetImagePath($imageName) {
    return $this->JoinPaths($this->pathToImages, $imageName . '.jpg');
    // TODO: See which extension exists, and return that one.
  }

  private function JoinPaths() {
    $slash = DIRECTORY_SEPARATOR;
    $arguments = func_get_args();
    $sections = preg_split(
            "@[/\\\\]@", implode('/', $arguments), null, PREG_SPLIT_NO_EMPTY);
    return implode($slash, $sections);
  }
}