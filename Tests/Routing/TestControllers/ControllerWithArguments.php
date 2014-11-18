<?php

/**
 * Class ControllerWithArguments
 */
class ControllerWithArguments
{
    private $first;
    private $second;

    /**
     * @param string $first
     * @param int    $second
     */
    function __construct($first, $second)
    {
        $this->first = $first;
        $this->second = $second;
    }

    /**
     * @return string
     */
    function getFirst()
    {
        return $this->first;
    }

    /**
     * @return int
     */
    function getSecond()
    {
        return $this->second;
    }
}