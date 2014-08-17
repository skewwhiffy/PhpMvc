<?php

abstract class BaseController {

  private $pathToViews;
  
  private $pathToModels;

  public function __construct() {
    $controllerName = get_class($this);
    $baseName = substr(
            $controllerName, 0, strlen($controllerName) - strlen('Controller'));
    $projectRoot = '../';
    $this->pathToViews = $this->JoinPaths($projectRoot, 'View', $baseName);
    $this->pathToModels = $this->JoinPaths($projectRoot, 'Model', $baseName);
  }
  
  protected function Render($model = null, $viewName = null) {
    require_once 'ViewRenderer.php';
    if ($viewName === null) {
      $callers = debug_backtrace();
      $viewName = $callers[1]['function'];
    }
    $viewPath = $this->JoinPaths($this->pathToViews, $viewName . 'View.php');
    $renderer = new ViewRenderer($viewPath);
    $renderer->Render($model);
  }
  
  protected function ImportModel($modelName = null){
    if ($modelName === null) {
      $callers = debug_backtrace();
      $modelName = $callers[1]['function'];
    }
    $modelPath = $this->JoinPaths($this->pathToModels, $modelName . 'Model.php');
    require_once($modelPath);
  }

  private function JoinPaths() {
    $slash = DIRECTORY_SEPARATOR;
    $arguments = func_get_args();
    $sections = preg_split(
            "@[/\\\\]@", implode('/', $arguments), null, PREG_SPLIT_NO_EMPTY);
    return implode($slash, $sections);
  }

}
