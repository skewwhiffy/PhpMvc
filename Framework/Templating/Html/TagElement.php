<?php
namespace Framework\Templating\Html;

require_once '/Templating/Tags/ViewTag.php';

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