<?php
require_once 'Includes.php';

use Framework\Routing\Request;

$request = new Request();

echo "REQUEST: \n";
var_dump($_REQUEST);

echo "SERVER: \n";
var_dump($_SERVER);