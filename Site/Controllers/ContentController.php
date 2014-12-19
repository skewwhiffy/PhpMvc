<?php

/**
 * Class ContentController
 */
class ContentController
{
    /**
     */
    public function __catchAll()
    {
        var_dump(func_get_args());
    }
}