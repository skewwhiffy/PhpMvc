<?php
require_once 'FileReader.php';

abstract class ControllerBase {

  private $baseName;

  private $fileReader;

  public function __construct() {
    $controllerName = get_class($this);
    $baseNameLength = strlen($controllerName) - strlen('Controller');
    $this->baseName = substr($controllerName, 0, $baseNameLength);
    $this->fileReader = new FileReader();
  }

  protected function Render($model = null, $viewName = null) {
    require_once 'ViewRenderer.php';
    if ($viewName === null) {
      $callers = debug_backtrace();
      $viewName = $callers [1] ['function'];
    }
    $renderer = new ViewRenderer("$this->baseName/$viewName");
    $renderer->RenderAndOutput($model);
  }

  protected function ImportModel($modelName = null) {
    if ($modelName === null) {
      $callers = debug_backtrace();
      $modelName = $callers [1] ['function'];
    }
    require_once ($this->fileReader->GetModelPath("$this->baseName/$modelName"));
  }
}
