<?php
namespace Framework\Templating\Tags;

/**
 * Interface ITagExtractor
 * @package Framework\Templating\Tags
 */
interface ITagExtractor{
    /**
     * @param $code
     *
     * @return ViewTag[]
     */
    public function getTags($code);

    /**
     * @param $code
     *
     * @return ViewTag[]
     */
    public function getElements($code);
}