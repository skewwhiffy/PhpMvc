<?php

class FilePathHelper {

  private $slash;

  public function __construct() {
    $this->slash = DIRECTORY_SEPARATOR;
  }

  public function JoinPaths() {
    $arguments = func_get_args();
    if (count($arguments) === 1 && is_array($arguments [0])) {
      return $this->JoinPathsArray($arguments [0]);
    } else {
      return $this->JoinPathsArray($arguments);
    }
  }

  private function JoinPathsArray($arguments) {
    $sections = preg_split("@[/\\\\]@", implode('/', $arguments), null, PREG_SPLIT_NO_EMPTY);
    return implode($this->slash, $sections);
  }

  public function FileExists($fileName) {
    if (file_exists($fileName)) {
      return $fileName;
    }
    return $this->FileExistsInternal('', explode($this->slash, $fileName));
  }

  private function FileExistsInternal($start, $exploded) {
    if (count($exploded) === 0) {
      return $start;
    }
    $next = $exploded [0];
    $nextSearch = $this->JoinPaths($start, $next);
    if ($next === '..') {
      $newExploded = array_slice($exploded, 1);
      return $this->FileExistsInternal($nextSearch, $newExploded);
    }
    $fileArray = glob($start . '/*', GLOB_NOSORT);
    $nextLowerCase = strtolower($nextSearch);
    foreach ( $fileArray as $file ) {
      if (strtolower($file) == $nextLowerCase) {
        $newStart = $file;
        $newExploded = array_slice($exploded, 1);
        return $this->FileExistsInternal($newStart, $newExploded);
      }
    }
    return false;
  }
}
