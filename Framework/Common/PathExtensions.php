<?php namespace Framework\Common;

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
    public function joinPaths()
    {
        $slash = DIRECTORY_SEPARATOR;
        $sections = $this->splitPath(implode('/', func_get_args()));
        return implode($slash, $sections);
    }

    /**
     * @param $path
     *
     * @return string[]
     */
    public function splitPath($path){
        return preg_split(
            "@[/\\\\]@",
            $path,
            null,
            PREG_SPLIT_NO_EMPTY);
    }
}