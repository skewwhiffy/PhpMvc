<?php
use Framework\Exceptions\InvalidRequestMethodException;
use Framework\Routing\Request;
use Framework\Routing\RequestMethod;

require_once __DIR__ . '/../Includes.php';

/**
 * Class RequestTests
 */
class RequestTest extends PHPUnit_Framework_TestCase
{
    public function testRequestGetsPopulatedWhenNull()
    {
        $request = new Request();

        $this->assertThat($_REQUEST, $this->identicalTo($request->getRequest()));
        $this->assertThat($_SERVER, $this->identicalTo($request->getServer()));
    }

    public function testRequestUriIsCorrect()
    {
        $url = 'url';
        $requestArray = ['hello' => 'baby'];
        $serverArray = ['REDIRECT_URL' => $url];

        $request = new Request($requestArray, $serverArray);

        $this->assertThat($request->getUri(), $this->equalTo($url));
    }

    public function testRequestMethodIsCorrect()
    {
        $possibleMethods = [
            'GET' => RequestMethod::GET,
            'POST' => RequestMethod::POST,
            'PUT' => RequestMethod::PUT,
            'PATCH' => RequestMethod::PATCH,
            'DELETE' => RequestMethod::DELETE,
            'COPY' => RequestMethod::COPY,
            'HEAD' => RequestMethod::HEAD,
            'OPTIONS' => RequestMethod::OPTIONS,
            'LINK' => RequestMethod::LINK,
            'UNLINK' => RequestMethod::UNLINK,
            'PURGE' => RequestMethod::PURGE,
            'TRACE' => RequestMethod::TRACE,
            'CONNECT' => RequestMethod::CONNECT
        ];
        foreach ($possibleMethods as $method => $expected)
        {
            $requestArray = [];
            $serverArray = ['REQUEST_METHOD' => $method];

            $request = new Request($requestArray, $serverArray);
            $actual = $request->getMethod();

            $this->assertThat($actual, $this->equalTo($expected));
        }
    }

    public function testInvalidMethodThrows()
    {
        $serverArray = ['REQUEST_METHOD' => 'not a method'];
        $requestArray = [];
        $this->setExpectedException(InvalidRequestMethodException::getClassName());

        $request = new Request($requestArray, $serverArray);
        $request->getMethod();
    }
}