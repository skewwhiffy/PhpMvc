<?php
namespace Framework\Templating\Html;

use Framework\Exceptions\UnrecognizedElementTypeException;
use Framework\Exceptions\UnrecognizedTagTypeException;
use Framework\Templating\Tags\ViewTag;


/**
 * Class Document
 * @package Framework\Templating\Html
 */
class Document
{
    /** var string */
    private $code;

    /** var IDocumentElement[] */
    private $elements;

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
        if ($this->elements !== null)
        {
            return $this->elements;
        }
        $tags = ViewTag::getTags($this->code);
        if (empty($tags))
        {
            return [new HtmlElement($this->code)];
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

    /**
     * @throws UnrecognizedElementTypeException
     * @throws UnrecognizedTagTypeException
     * @internal param $model
     *
     * @returns string
     */
    public function renderExpressionTags()
    {
        $rendered = '';
        foreach ($this->getElements() as $element)
        {
            if ($element instanceof TagElement)
            {
                /** @var TagElement $tagElement */
                $tagElement = $element;
                $tag = $tagElement->getTag();
                $key = $tag->getKey();
                $value = $tag->getValue();
                if (empty($key))
                {
                    $rendered .= "<?php echo $value;?>";
                    continue;
                }
                throw new UnrecognizedTagTypeException($tag);
            }
            if ($element instanceof HtmlElement)
            {
                /** @var HtmlElement $htmlElement */
                $htmlElement = $element;
                $rendered .= $htmlElement->getCode();
                continue;
            }
            throw new UnrecognizedElementTypeException($element);
        }

        return $rendered;
    }
}