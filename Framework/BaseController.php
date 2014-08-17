<?php

abstract class BaseController {

  private $pathToViews;
  
  private $pathToModels;

  public function __construct() {
    $controllerName = get_class($this);
    $baseName = substr(
            $controllerName, 0, strlen($controllerName) - strlen('Controller'));
    $projectRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
    $this->pathToViews = $this->JoinPaths($projectRoot, 'View', $baseName);
    $this->pathToModels = $this->JoinPaths($projectRoot, 'Model', $baseName);
  }
  
  protected function Render($model = null, $viewName = null) {
    require_once '/ViewRenderer.php';
    if ($viewName === null) {
      $viewName = debug_backtrace()[1]['function'];
    }
    $viewPath = $this->JoinPaths($this->pathToViews, $viewName . 'View.php');
    $renderer = new ViewRenderer($viewPath);
    $renderer->Render($model);
  }
  
  protected function ImportModel($modelName = null){
    if ($modelName === null) {
      $modelName = debug_backtrace()[1]['function'];
    }
    $modelPath = $this->JoinPaths($this->pathToModels, $modelName . 'Model.php');
    require_once($modelPath);
  }

  private function JoinPaths() {
    $slash = DIRECTORY_SEPARATOR;
    $sections = preg_split(
            "@[/\\\\]@", implode('/', func_get_args()), null, PREG_SPLIT_NO_EMPTY);
    return implode($slash, $sections);
  }

}
