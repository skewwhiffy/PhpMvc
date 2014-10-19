<?php

namespace Framework\Routing;

/**
 * Class Routing
 * @package Framework\Routing
 */
class Request
{
    private $server;

    /**
     * @param array $server
     */
    public function __construct($server = null)
    {
        if ($server === null){
            $server = $_SERVER;
        }
        $this->server = $server;
    }

    public function getUri(){
        return $this->server['REQUEST_URI'];
    }
}