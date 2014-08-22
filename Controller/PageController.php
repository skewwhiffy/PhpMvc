<?php

class PageController extends ControllerBase {
  public function Fetch($page) {
    $this->ImportModel();
    $model = new FetchModel();
    $model -> title = "Cacahuetes - jazz for all occasions";
    
    $paragraphs = array ();
    $paragraph = new Paragraph();
    $paragraph -> heading = "Jazz - nice";
    $paragraph -> text = "You want jazz; you got it";
    $paragraphs[] = new Paragraph();
    $model -> paragraphs = $paragraphs;
    $this->Render($model);;
  }
}