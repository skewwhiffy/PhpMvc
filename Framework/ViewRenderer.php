<?php

class ViewRenderer {
  
  private $pathToFile;
  
  public function __construct($pathToFile) {
    $this->pathToFile = $pathToFile;
  }
  
  public function Render($model) {
    $openTagEcho = '<@=';
    $closeTagEcho = '@>';
    $openTagNoEcho = '$M';
    $closeTagNoEcho = '__';
    $viewCode = file_get_contents($this->pathToFile);
    /*
     * Renderer replaces tags like this:
     *  <@=title@>
     * with
     *  <?php echo $model->title; ?>
     */
    $modelEchoesReplaced = $this->Replace(
            $viewCode,
            $openTagEcho,
            $closeTagEcho,
            '<?php echo $model->%s;?>');
    /*
     * Renderer replaces tags like this:
     *  <?php echo $Mtitle__; ?>
     * with
     *  <?php echo $model->title; ?>
     */
    $modelNoEchoesReplaced = $this->Replace(
            $modelEchoesReplaced,
            $openTagNoEcho,
            $closeTagNoEcho,
            '$model->%s');
    $tempFile = tmpfile();
    fwrite($tempFile, $modelNoEchoesReplaced);
    $metaData = stream_get_meta_data($tempFile);
    include $metaData['uri'];
  }
  
  private function Replace($source, $openTag, $closeTag, $codeFormat) {
    while (true){
      $indexOfOpenTag = strpos($source, $openTag);
      if ($indexOfOpenTag === false) {
        break;
      }
      $indexOfCloseTag = strpos($source, $closeTag);
      if ($indexOfCloseTag === false) {
        throw new RuntimeException("$openTag not closed in $this->pathToFile");
      }
      $code = substr(
              $source,
              $indexOfOpenTag + strlen($openTag),
              $indexOfCloseTag - $indexOfOpenTag - strlen($openTag));
      $source = substr($source, 0, $indexOfOpenTag)
              . sprintf($codeFormat, $code)
              . substr($source, $indexOfCloseTag + strlen($closeTag));
    }
    return $source;
  }
}