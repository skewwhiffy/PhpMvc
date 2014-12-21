<?php namespace Framework\Common;

/**
 * Class StringExtensions
 * @package Framework\Common
 */
class StringExtensions
{
    /**
     * @param string $source
     * @param string $end
     * @return bool
     */
    public function endsWith($source, $end)
    {
        $endLength = strlen($end);
        $sourceLength = strlen($source);
        if ($endLength > $sourceLength)
        {
            return false;
        }
        if (empty($end))
        {
            return true;
        }
        return (substr($source, $sourceLength - $endLength) === $end);
    }
}