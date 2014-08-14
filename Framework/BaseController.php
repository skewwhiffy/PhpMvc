<?php

abstract class BaseController {

  private $pathToViews;

  public function __construct() {
    $controllerName = get_class($this);
    $baseName = substr(
            $controllerName, 0, strlen($controllerName) - strlen('Controller'));
    $projectRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
    $pathToView = $this->JoinPaths($projectRoot, 'View', $baseName);
    echo $pathToView;
  }
  
  public function Render($model = null) {
    // Render the view, using the model
    echo 'DEBUG:';
    echo debug_backtrace()[1]['function'];
  }

  private function JoinPaths() {
    $slash = DIRECTORY_SEPARATOR;
    $sections = preg_split(
            "@[/\\\\]@", implode('/', func_get_args()), null, PREG_SPLIT_NO_EMPTY);
    return implode($slash, $sections);
  }

}
