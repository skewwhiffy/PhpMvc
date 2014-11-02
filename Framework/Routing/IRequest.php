<?php namespace Framework\Routing;

/**
 * Class Routing
 * @package Framework\Routing
 */
interface IRequest
{
    /**
     * @return string
     */
    public function getUri();

    /**
     * @throws \Framework\Exceptions\InvalidRequestMethodException
     * @return int
     */
    public function getMethod();

    /**
     * @return array
     */
    public function getRequest();

    /**
     * @return array
     */
    public function getServer();
}