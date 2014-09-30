<?php
namespace Framework\Templating\Html;

require_once '/Templating/Tags/ViewTag.php';

use Framework\Templating\Tags\ViewTag;


/**
 * Class Document
 * @package Framework\Templating\Html
 */
class Document
{
    /** var string */
    private $code;

    /**
     * @param string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @return IDocumentElement[]
     */
    public function getElements()
    {
        $tags = ViewTag::getTags($this->code);
        if (empty($tags)){
            return [new HtmlElement($this->code)];
        }
        $elements = [];
        foreach($tags as $tag){
            $elements[] = new HtmlElement($tag->getCodeBefore());
            $elements[] = new TagElement($tag);
        }
        /** @var ViewTag $finalTag */
        $finalTag = array_pop($tags);
        $elements[] = new HtmlElement($finalTag->getCodeAfter());
        return $elements;
    }
}