<?php

require_once 'ViewTag.php';

class ViewRenderer {

  private $viewName;
  private $modelsData = array();
  private $openTag = '<@';
  private $closeTag = '@>';
  private $fileReader;

  public function __construct($viewName) {
    $this->fileReader = new FileReader();
    $this->viewName = $viewName;
  }

  public function RenderAndOutput($model) {
    $viewCode = $this->fileReader->GetViewCode($this->viewName);
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

    $inTemplate = $this->ApplyTemplate($rendered);

    return $inTemplate;
  }

  private function ApplyTemplate($rendered) {
    $tags = $this->GetAllAtTags($rendered);
    $templateCode = $this->GetTemplateCode($tags);
    if ($templateCode === false) {
      return $rendered;
    }
    $templateModel;
    foreach ($this->modelsData as $modelName => $modelValue) {
      eval("$modelName = \$modelValue;");
    }
    foreach ($tags as $tag) {
      if (strcasecmp($tag->key, 'templatemodel') === 0) {
        eval("\$templateModel = $tag->value;");
        break;
      }
    }
    $renderedTemplate = $this->Render($templateModel, $templateCode);
    $templateTags = $this->GetAllAtTags($renderedTemplate);
    $contents = $this->GetContents($rendered, $tags);
    foreach ($templateTags as $tag) {
      if (strcasecmp($tag->key, 'contentholder') === 0) {
        $renderedTemplate = str_replace($tag->raw, $contents[$tag->value], $renderedTemplate);
      }
    }
    return $renderedTemplate;
  }

  private function GetContents($rendered, $tags) {
    $contents = array();
    $startIndex;
    $contentName;
    foreach ($tags as $tag) {
      if (strcasecmp($tag->key, 'content') === 0) {
        $startIndex = $tag->endIndex;
        $contentName = $tag->value;
      }
      if (strcasecmp($tag->key, 'endcontent') === 0) {
        $endIndex = $tag->startIndex - 1;
        $content = substr($rendered, $startIndex, $endIndex - $startIndex);
        $contents[$contentName] = $content;
      }
    }
    return $contents;
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
      $templateName = str_replace('\'', '', $tag->value);
      ;
    }
    if ($templateName === false) {
      return $templateName;
    }
    return $this->fileReader->GetViewCode($templateName);
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
        throw new RuntimeException("$openTag not closed in $this->viewName");
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
