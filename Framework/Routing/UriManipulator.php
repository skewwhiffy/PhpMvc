<?php namespace Framework\Routing;

/**
 * Class UriManipulator
 * @package Framework\Routing
 */
class UriManipulator
{
    /**
     * @param string $uri
     *
     * @return string[]
     */
    public function split($uri)
    {
        $split = explode('//', $uri);
        $nonEmpty = [];
        foreach ($split as $element)
        {
            if (!empty($element))
            {
                $nonEmpty[] = $element;
            }
        }
        return $nonEmpty;
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    public function getExtension($uri)
    {
        $split = $this->split($uri);
        $filename = array_pop($split);
        $lastDotIndex = strrpos($filename, '.');
        return $lastDotIndex === false
            ? ''
            : substr($filename, $lastDotIndex + 1);
    }
}