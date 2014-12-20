<?php

use Framework\Routing\ControllerRouting;
use Framework\Routing\IRequest;
use Framework\Routing\Request;
use Framework\FileIo\FileReader;
use Framework\FileIo\IFileReader;

require_once __DIR__ . '/../Includes.php';
require_once __DIR__ . '/TestControllers/ControllerWithActionController.php';
require_once __DIR__ . '/TestControllers/ControllerWithoutActionController.php';
require_once __DIR__ . '/TestControllers/ControllerWithCatchAllMethodController.php';

/**
 * Class ControllerRoutingTest
 */
class ControllerRoutingTest extends PHPUnit_Framework_TestCase
{
    const controller = 'controller';
    const controllerClass = 'controllerController';
    const action = 'action';

    /**
     * @return IFileReader | PHPUnit_Framework_MockObject_MockObject
     */
    private function getControllerFileReaderMock()
    {
        return $this->getMock('Framework\FileIo\IFileReader');
    }

    /**
     * @param string $url
     *
     * @return IRequest
     */
    private function getRequestMock($url = null)
    {
        if ($url === null)
        {
            $url = self::controller . '/' . self::action;
        }
        $requestMock = $this->getMock('Framework\Routing\IRequest');
        $requestMock->expects($this->any())
            ->method('getUri')
            ->willReturn($url);
        return $requestMock;
    }

    public function testConstructWorksWithNoArguments()
    {
        $routing = new ControllerRouting(new FileReader(__DIR__));
        $request = $routing->getRequest();

        $this->assertThat($request, $this->isInstanceOf(get_class(new Request())));
    }

    public function testExtractsControllerNameAndActionCorrectly()
    {
        $controllers = $this->getControllerFileReaderMock();
        $request = $this->getRequestMock();
        $routing = new ControllerRouting($controllers, $request);

        $this->assertThat($routing->controllerName(), $this->equalTo(self::controller));
        $this->assertThat($routing->controllerClassName(), $this->equalTo(self::controllerClass));
        $this->assertThat($routing->actionName(), $this->equalTo(self::action));
    }

    public function testShouldInvokeReturnsFalseWhenControllerDoesNotExist()
    {
        $controllers = $this->getControllerFileReaderMock();
        $controllers->expects($this->any())
            ->method('getFiles')
            ->willReturn([]);
        $routing = new ControllerRouting($controllers, $this->getRequestMock());

        $this->assertThat($routing->shouldInvoke(), $this->isFalse());
    }

    public function testShouldInvokeReturnsFalseWhenActionDoesNotExist()
    {
        $request = $this->getRequestMock('controllerWithoutAction/action');
        $controllers = $this->getControllerFileReaderMock();
        $controllers->expects($this->any())
            ->method('getFiles')
            ->willReturn(['ControllerWithoutActionController.php']);
        $routing = new ControllerRouting($controllers, $request);

        $this->assertThat($routing->shouldInvoke(), $this->isFalse());
    }

    public function testShouldInvokeReturnsTrueWhenActionExists()
    {
        $request = $this->getRequestMock('controllerWithAction/action');
        $controllers = $this->getControllerFileReaderMock();
        $controllers->expects($this->any())
            ->method('getFiles')
            ->willReturn(['ControllerWithActionController.php']);
        $routing = new ControllerRouting($controllers, $request);

        $this->assertThat($routing->shouldInvoke(), $this->isTrue());
    }

    public function testShouldInvokeIsCaseInsensitive()
    {
        $request = $this->getRequestMock('CONTROLLERWITHACTION/ACTION');
        $controllers = $this->getControllerFileReaderMock();
        $controllers->expects($this->any())
            ->method('getFiles')
            ->willReturn(['ControllerWithActionController.php']);
        $routing = new ControllerRouting($controllers, $request);

        $this->assertThat($routing->shouldInvoke(), $this->isTrue());
    }

    public function testShouldInvokeIfControllerHasCatchAllMethod()
    {
        $request = $this->getRequestMock('controllerWithCatchAllMethod/blah/dee/blah');
        $controllers = $this->getControllerFileReaderMock();
        $controllers->expects($this->any())
            ->method('getFiles')
            ->willReturn(['ControllerWithCatchAllMethodController.php']);
        $routing = new ControllerRouting($controllers, $request);

        $this->assertThat($routing->shouldInvoke(), $this->isTrue());
    }

    public function testShouldExtractRemainingArgumentsCorrectly()
    {
        $request = $this->getRequestMock('CONTROLLERWITHACTION/ACTION/1/2/3');
        $controllers = $this->getControllerFileReaderMock();
        $controllers->expects($this->any())
            ->method('getFiles')
            ->willReturn(['ControllerWithActionController.php']);
        $routing = new ControllerRouting($controllers, $request);

        $result = $routing->actionArgs();

        $this->assertThat(sizeof($result), $this->equalTo(3));
        $this->assertThat($result[0], $this->equalTo('1'));
        $this->assertThat($result[1], $this->equalTo('2'));
        $this->assertThat($result[2], $this->equalTo('3'));
    }
}