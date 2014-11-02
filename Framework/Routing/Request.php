<?php namespace Framework\Routing;
use Framework\Exceptions\InvalidRequestMethodException;

/**
 * Class Routing
 * @package Framework\Routing
 */
class Request implements IRequest
{
    private $server;
    private $request;

    /**
     * @param array $request
     * @param array $server
     */
    public function __construct($request = null, $server = null)
    {
        if ($request === null)
        {
            $request = $_REQUEST;
        }
        if ($server === null)
        {
            $server = $_SERVER;
        }
        $this->request = $request;
        $this->server = $server;
    }

    /**
     * @return array
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->server['REDIRECT_URL'];
    }

    /**
     * @throws \Framework\Exceptions\InvalidRequestMethodException
     * @return int
     */
    public function getMethod()
    {
        $method = $this->server['REQUEST_METHOD'];
        switch (strtolower($method))
        {
            case 'get':
                return RequestMethod::GET;
            case 'post':
                return RequestMethod::POST;
            case 'put':
                return RequestMethod::PUT;
            case 'patch':
                return RequestMethod::PATCH;
            case 'delete':
                return RequestMethod::DELETE;
            case 'copy':
                return RequestMethod::COPY;
            case 'head':
                return RequestMethod::HEAD;
            case 'options':
                return RequestMethod::OPTIONS;
            case 'link':
                return RequestMethod::LINK;
            case 'unlink':
                return RequestMethod::UNLINK;
            case 'purge':
                return RequestMethod::PURGE;
            case 'trace':
                return RequestMethod::TRACE;
            case 'connect':
                return RequestMethod::CONNECT;
            default:
                throw new InvalidRequestMethodException($method);
        }
    }
}