<?php
require_once 'Includes.php';

use Framework\Exceptions\NotImplementedException;
use Framework\Routing\ControllerRouting;
use Framework\Routing\MethodInvoker;
use Framework\Routing\Request;
use Framework\FileIo\FileReader;

$request = new Request();
$controllers = new FileReader(__DIR__ . '/../Site/Controllers');
$routing = new ControllerRouting($controllers, $request);
if (!$routing->shouldInvoke())
{
    throw new NotImplementedException('I don\'t know what to do');
}
$invoker = new MethodInvoker($controllers);
$controller = $invoker->getInstance($routing->controllerClassName(), []);
$invoker->invokeMethodOnInstance($controller, $routing->actionName(), $routing->actionArgs());