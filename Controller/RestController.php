<?php

class RestController extends ControllerBase {

  private $fileReader;

  public function __construct() {
    parent::__construct();
    $this->fileReader = new FileReader();
  }

  public function Index() {
    $test = array ();
    $test ['page'] = 'Hello, this is planet Earth!';
    $test ['method'] = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
    echo json_encode($test);
  }
}