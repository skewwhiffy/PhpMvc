<?php

require_once 'ViewTag.php';

class ViewRenderer {

  private $pathToFile;
  private $modelsData = array();
  private $openTag = '<@';
  private $closeTag = '@>';

  public function __construct($pathToFile) {
    $this->pathToFile = $pathToFile;
  }

  public function RenderAndOutput($model) {
    $viewCode = file_get_contents($this->pathToFile);
    $toOutput = $this->Render($model, $viewCode);
    $tempFile = tmpfile();
    $tempFileLocation = stream_get_meta_data($tempFile)['uri'];
    fwrite($tempFile, $toOutput);
    foreach ($this->modelsData as $modelName => $modelValue) {
      eval("$modelName = \$modelValue;");
    }
    include $tempFileLocation;
  }

  /*
   * <@=title@> gets replaced with <?php echo $model->title; ?>
   * A random model name is generated for the $model variable.
   * Template is applied if required.
   */

  private function Render($model, $viewCode) {
    $tags = $this->GetAllAtTags($viewCode);

    $openTagEcho = '<@=';
    $closeTagEcho = '@>';
    $modelName = '$mm' . uniqid();

    $modelEchoesReplaced = $this->Replace(
            $viewCode, $openTagEcho, $closeTagEcho, "<?php echo $modelName->%s;?>");
    $this->modelsData[$modelName] = $model;
    $rendered = str_replace('$model', $modelName, $modelEchoesReplaced);

    $inTemplate = $this->ApplyTemplate($rendered, $tags);

    return $inTemplate;
  }

  private function ApplyTemplate($rendered, $tags) {
    $templateCode = $this->GetTemplateCode($tags);
    return $rendered;
  }

  private function GetTemplateCode($tags) {
    $templateName = false;
    foreach ($tags as $tag) {
      if (strcasecmp($tag->key, 'template') !== 0) {
        continue;
      }
      if ($templateName !== false) {
        throw new RuntimeException('Template name not unique');
      }
      $templateName = $tag->value;
    }
    if ($templateName === false) {
      return $templateName;
    }
    $templateName = str_replace('\'', '', $templateName);
    echo "You've found the template name; now get and render the template\n";
    var_dump($templateName);
    exit;
  }

  private function GetAllAtTags($viewCode) {
    $tags = array();
    $currentIndex = 0;
    while ($currentIndex < strlen($viewCode)) {
      $nextTag = $this->GetNextTag($viewCode, $currentIndex);
      if ($nextTag === null) {
        break;
      }
      $tags[] = $nextTag;
      $currentIndex = $nextTag->endIndex;
    }
    return $tags;
  }

  private function GetNextTag($viewCode, $startIndex) {
    $startIndex = strpos($viewCode, $this->openTag, $startIndex);
    return $startIndex === false ? null : new ViewTag($this->openTag, $this->closeTag, $startIndex, $viewCode);
  }

  private function Replace($source, $openTag, $closeTag, $codeFormat) {
    while (true) {
      $indexOfOpenTag = strpos($source, $openTag);
      if ($indexOfOpenTag === false) {
        break;
      }
      $indexOfCloseTag = strpos($source, $closeTag, $indexOfOpenTag + 1);
      if ($indexOfCloseTag === false) {
        throw new RuntimeException("$openTag not closed in $this->pathToFile");
      }
      $code = substr(
              $source, $indexOfOpenTag + strlen($openTag), $indexOfCloseTag - $indexOfOpenTag - strlen($openTag));
      $source = substr($source, 0, $indexOfOpenTag)
              . sprintf($codeFormat, $code)
              . substr($source, $indexOfCloseTag + strlen($closeTag));
    }
    return $source;
  }

}
