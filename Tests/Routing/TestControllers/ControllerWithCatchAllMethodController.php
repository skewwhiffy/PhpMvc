<?php

/**
 * Class ControllerWithCatchAllMethodController
 */
class ControllerWithCatchAllMethodController
{
    /**
     * @return array
     */
    public function __catchAll()
    {
        return func_get_args();
    }
}