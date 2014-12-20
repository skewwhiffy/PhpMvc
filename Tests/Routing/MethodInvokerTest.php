<?php
use Framework\Routing\MethodInvoker;
use Framework\FileIo\FileReader;
use Framework\FileIo\IFileReader;

require_once __DIR__ . '/../Includes.php';

/**
 * Class MethodInvokerTest
 */
class MethodInvokerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return FileReader
     */
    private function getTestControllersFileReader()
    {
        return new FileReader(__DIR__ . '/TestControllers');
    }

    /**
     * @param null $controllers
     *
     * @return MethodInvoker
     */
    private function getInvoker($controllers = null)
    {
        if ($controllers === null)
        {
            $controllers = $this->getTestControllersFileReader();
        }
        return new MethodInvoker($controllers);
    }

    public function testGetInstanceCallsInclude()
    {
        /** @var IFileReader|PHPUnit_Framework_MockObject_MockObject $controllers */
        $controllers = $this->getMock('Framework\FileIo\IFileReader');
        $controllers
            ->expects($this->once())
            ->method('includeFile');
        $invoker = new MethodInvoker($controllers);

        $testController = __CLASS__;
        $instance = $invoker->getInstance($testController);
        $this->assertThat($instance, $this->isInstanceOf(__CLASS__));;
    }

    public function testGetInstanceWorksWithArguments()
    {
        $invoker = $this->getInvoker();
        $first = 'first';
        $second = 2;
        $controller = 'ControllerWithArguments';

        /** @var ControllerWithArguments $instance */
        $instance = $invoker->getInstance($controller, [$first, $second]);;

        $this->assertThat($instance, $this->isInstanceOf($controller));
        $this->assertThat($instance->getFirst(), $this->equalTo($first));
        $this->assertThat($instance->getSecond(), $this->equalTo($second));
    }

    public function testInvokeMethodOnInstance()
    {
        $controllers = $this->getTestControllersFileReader();
        $invoker = $this->getInvoker($controllers);
        $controllers->includeFile('ControllerWithActionController.php');
        $controller = new ControllerWithActionController();
        $expected = $controller->action();

        $result = $invoker->invokeMethodOnInstance(
            $controller,
            'action',
            []);

        $this->assertThat($result, $this->equalTo($expected));
    }

    public function testControllerWithCatchAllMethodWorks(){
        $controllers = $this->getTestControllersFileReader();
        $invoker = $this->getInvoker($controllers);
        $controller = 'ControllerWithCatchAllMethodController';

        /** @var ControllerWithCatchAllMethodController $instance */
        $instance = $invoker->getInstance($controller);
        $result = $invoker->invokeMethodOnInstance($instance, 'blah', ['argument']);

        $this->assertThat(count($result), $this->equalTo(2));
        $this->assertThat($result[0], $this->equalTo('blah'));
        $this->assertThat(count($result[1]), $this->equalTo(1));
        $this->assertThat($result[1][0], $this->equalTo('argument'));
    }
}