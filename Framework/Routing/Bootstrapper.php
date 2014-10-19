<?php
require_once '..\Includes.php';

use Framework\Routing\Request;

$request = new Request();

echo $request->getUri();