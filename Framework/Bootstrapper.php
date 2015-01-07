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
    $controllerClassName = 'HomeController';
    $actionName = 'index';
    $actionArgs = [];
    //throw new NotImplementedException('I don\'t know what to do');
}
else
{
    $controllerClassName = $routing->controllerClassName();
    $actionName = $routing->actionName();
    $actionArgs = $routing->actionArgs();
}
$invoker = new MethodInvoker($controllers);
$controller = $invoker->getInstance($controllerClassName, []);
$invoker->invokeMethodOnInstance($controller, $actionName, $actionArgs);