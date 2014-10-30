<?php

namespace Framework\Routing;

/**
 * Class Routing
 * @package Framework\Routing
 */
class Request
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
     * @return array|null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array|null
     */
    public function getServer()
    {
        return $this->server;
    }
}