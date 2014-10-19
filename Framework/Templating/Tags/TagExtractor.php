<?php
namespace Framework\Templating\Tags;
use Framework\Constants\Constants;
use Framework\Templating\Html\HtmlElement;
use Framework\Templating\Html\IDocumentElement;
use Framework\Templating\Html\TagElement;

/**
 * Class TagExtractor
 */
class TagExtractor implements ITagExtractor
{
    /**
     * @param string $code
     *
     * @return ViewTag[]
     */
    public function getTags($code)
    {
        $tags = [];
        $remainingCode = $code;
        while (strpos($remainingCode, Constants::openTag) !== false)
        {
            $newTag = new ViewTag($remainingCode);
            $tags[] = $newTag;
            $remainingCode = $newTag->getCodeAfter();
        }
        return $tags;
    }

    /**
     * @return IDocumentElement[]
     */
    public function getElements($code)
    {
        $tags = $this->getTags($code);
        if (empty($tags))
        {
            return [new HtmlElement($code)];
        }
        $elements = [];
        foreach ($tags as $tag)
        {
            $elements[] = new HtmlElement($tag->getCodeBefore());
            $elements[] = new TagElement($tag);
        }
        /** @var ViewTag $finalTag */
        $finalTag = array_pop($tags);
        $elements[] = new HtmlElement($finalTag->getCodeAfter());
        $this->elements = $elements;
        return $elements;
    }
}