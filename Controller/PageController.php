<?php

class PageController extends ControllerBase {

  public function Fetch($page) {
    $this->ImportModel();
    $model = new FetchModel();
    $model->title = "Cacahuetes - jazz for all occasions";

    $paragraphs = array();
    $paragraph = new Paragraph();
    $paragraph->heading = "Jazz - nice";
    $paragraph->text = "Coming soon. Email kennyhung at live dot co dot uk for more information";
    $paragraphs[] = $paragraph;
    $model->paragraphs = $paragraphs;
    $this->Render($model);
  }

}
