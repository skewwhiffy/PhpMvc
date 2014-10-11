<?php
namespace Framework\Templating\Html;

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
        if ($this->elements !== null){
            return $this->elements;
        }
        $tags = ViewTag::getTags($this->code);
        if (empty($tags)) {
            return [new HtmlElement($this->code)];
        }
        $elements = [];
        foreach ($tags as $tag) {
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
     * @throws \ErrorException
     * @internal param $model
     *
     * @returns string
     */
    public function renderExpressionTags()
    {
        $rendered = '';
        foreach($this->getElements() as $element){
            if (is_a($element, 'Framework\Templating\Html\TagElement')){
                /** @var TagElement $tagElement */
                $tagElement = $element;
                $tag = $tagElement->getTag();
                $key = $tag->getKey();
                $value = $tag->getValue();
                if (empty($key)) {
                    $rendered .= "<?php echo $value;?>";
                    continue;
                }
                $rendered .= $tag->getContents();
            }
            if (is_a($element, 'Framework\Templating\Html\HtmlElement')) {
                /** @var HtmlElement $htmlElement */
                $htmlElement = $element;
                $rendered .= $htmlElement->getCode();
                continue;
            }
            var_dump($element);
            throw new \ErrorException('Unrecognized element type: ' . gettype($element));
        }

        return $rendered;
    }
}