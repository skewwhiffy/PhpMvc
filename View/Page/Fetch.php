<@ template = 'Common/TemplateMaster' @>
<@ templateModel = $model @>
<@ content = 'main' @>
<h1><@=title@></h1>
<?php
foreach ($model->paragraphs as $paragraph) {
  echo "<h2>$paragraph->heading</h2>";
  echo "<p>$paragraph->text</p>";
}
?>
<@ endContent @>