<?php
namespace Framework\Templating\Html;

use \Framework\Templating\Tags\ViewTag;

/**
 * Class TagElement
 */
class TagElement implements IDocumentElement
{
    private $tag;

    /**
     * @param ViewTag $tag
     */
    public function __construct($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return ViewTag
     */
    public function getTag(){
        return $this->tag;
    }
}