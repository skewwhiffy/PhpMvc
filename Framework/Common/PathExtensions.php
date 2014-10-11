<?php

namespace Framework\Common;

/**
 * Interface IPathExtensions
 * @package Framework\Common
 */
interface IPathExtensions{
    function joinPaths();
}

/**
 * Class PathExtensions
 * @package Framework\Common
 */
class PathExtensions
{
    /**
     * @return string
     * @internal param string $path,...
     */
    public function joinPaths(){
        $slash = DIRECTORY_SEPARATOR;
        $sections = preg_split(
            "@[/\\\\]@",
            implode('/', func_get_args()),
            null,
            PREG_SPLIT_NO_EMPTY);
        return implode($slash, $sections);
    }
}