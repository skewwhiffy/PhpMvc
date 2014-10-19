<?php
namespace Framework\Templating\Html;

use Framework\Exceptions\UnrecognizedElementTypeException;
use Framework\Exceptions\UnrecognizedTagTypeException;
use Framework\Templating\Tags\ITagExtractor;
use Framework\Templating\Tags\TagExtractor;

/**
 * Class Document
 * @package Framework\Templating\Html
 */
class Document
{
    /** @var string */
    private $code;

    /** @var IDocumentElement[] */
    private $elements;

    /** @var bool */
    private $hasTemplate;

    /** @var array */
    private $content;

    /** @var ITagExtractor */
    private $tagExtractor;

    /** @var string */
    private $templateName;

    /**
     * @param string        $code
     * @param ITagExtractor $tagExtractor
     */
    public function __construct($code, $tagExtractor = null)
    {
        if ($tagExtractor === null)
        {
            $tagExtractor = new TagExtractor();
        }
        $this->tagExtractor = $tagExtractor;
        $this->code = $code;
    }

    /**
     * @param Document $contentDocument
     */
    public function addContent($contentDocument)
    {
        $newElements = [];
        $content = $contentDocument->getContent();
        foreach ($this->getElements() as $element)
        {
            if ($element instanceof TagElement)
            {
                /** @var TagElement $tagElement */
                $tagElement = $element;
                $tag = $tagElement->getTag();
                $key = $tag->getKey();
                if (strcasecmp($key, 'container') === 0)
                {
                    $value = $tag->getValue();
                    $newElements[] = new HtmlElement($content[$value]);
                    continue;
                }
            }
            $newElements[] = $element;
        }
        $this->elements = $newElements;
    }

    /**
     * @return IDocumentElement[]
     */
    public function getElements()
    {
        if ($this->elements === null)
        {
            $this->elements = $this->tagExtractor->getElements($this->code);
        }
        return $this->elements;
    }

    /**
     * @throws UnrecognizedElementTypeException
     * @throws UnrecognizedTagTypeException
     * @internal param $model
     *
     * @returns string
     */
    public function render()
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

    /**
     * @return bool
     */
    public function hasTemplate()
    {
        if ($this->hasTemplate === null)
        {
            $this->populateTemplateName();
        }
        return $this->hasTemplate;
    }

    /**
     * @return string
     */
    public function templateName()
    {
        if ($this->templateName === null)
        {
            $this->populateTemplateName();
        }
        return $this->templateName;
    }

    private function populateTemplateName(){

        foreach ($this->getElements() as $element)
        {
            if ($element instanceof TagElement)
            {
                /** @var TagElement $tagElement */
                $tagElement = $element;
                if (strcasecmp($tagElement->getTag()->getKey(), 'template') === 0)
                {
                    $this->hasTemplate = true;
                    $this->templateName = $tagElement->getTag()->getValue();
                    return;
                }
            }
        }
        $this->hasTemplate = false;
    }

    /**
     * @return array
     */
    public function getContent()
    {
        if ($this->content !== null)
        {
            return $this->content;
        }
        $this->content = [];
        $insideContent = false;
        $content = '';
        $key = '';
        foreach ($this->getElements() as $element)
        {
            if ($element instanceof TagElement)
            {
                /** @var TagElement $tagElement */
                $tagElement = $element;
                $tag = $tagElement->getTag();
                if ($insideContent && strcasecmp($tag->getKey(), 'endcontent') === 0)
                {
                    $this->content[$key] = $content;
                    $insideContent = false;
                    $content = '';
                }
                if (strcasecmp($tag->getKey(), 'content') !== 0)
                {
                    continue;
                }
                $key = $tag->getValue();
                $insideContent = true;
                $content = '';
            }
            if ($element instanceof HtmlElement)
            {
                /** @var HtmlElement */
                $htmlElement = $element;
                if ($insideContent)
                {
                    $content .= $htmlElement->getCode();
                }
            }
        }
        return $this->content;
    }

    /**
     * @return bool
     */
    public function isTemplate()
    {
        foreach ($this->getElements() as $element)
        {
            if (!($element instanceof TagElement))
            {
                continue;
            }
            /** var TagElement */
            $viewTag = $element;
            $tag = $viewTag->getTag();
            if (strcasecmp($tag->getKey(), 'container') === 0)
            {
                return true;
            }
        }
        return false;
    }
}