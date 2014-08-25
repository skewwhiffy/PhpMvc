<?php

class FilePathHelper {

  private $slash;

  public function __construct() {
    $this->slash = DIRECTORY_SEPARATOR;
  }

  public function JoinPaths() {
    $arguments = func_get_args();
    if (count($arguments) === 1 && is_array($arguments[0])) {
      return $this->JoinPathsArray($arguments[0]);
    } else {
      return $this->JoinPathsArray($arguments);
    }
  }

  private function JoinPathsArray($arguments) {
    $sections = preg_split(
            "@[/\\\\]@", implode('/', $arguments), null, PREG_SPLIT_NO_EMPTY);
    return implode($this->slash, $sections);
  }

  public function FileExists($fileName, $caseSensitive = false) {

    if (file_exists($fileName)) {
      return $fileName;
    }
    if ($caseSensitive) {
      return false;
    }

    $directoryName = dirname($fileName);
    $fileArray = glob($directoryName . '/*', GLOB_NOSORT);
    $fileNameLowerCase = strtolower($fileName);
    foreach ($fileArray as $file) {
      if (strtolower($file) == $fileNameLowerCase) {
        return $file;
      }
    }
    return false;
  }

}
