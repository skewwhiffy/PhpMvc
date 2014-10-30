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

    /** @var string */
    private $templateName;

    /** @var string */
    private $modelName;

    /** @var ITagExtractor */
    private $tagExtractor;

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
        $this->modelName = 'model';
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

    private function populateTemplateName()
    {

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
        $contentKey = '';
        foreach ($this->getElements() as $element)
        {
            if ($element instanceof TagElement)
            {
                /** @var TagElement $tagElement */
                $tagElement = $element;
                $tag = $tagElement->getTag();
                $tagKey = $tag->getKey();

                if ($insideContent)
                {
                    if (strcasecmp($tagKey, 'endcontent') === 0)
                    {
                        $this->content[$contentKey] = $content;
                        $insideContent = false;
                        $content = '';
                    }
                    elseif (empty($tagKey))
                    {
                        $content .= '<?php echo ' . $tag->getValue() . ';?>';
                    }
                    else
                    {
                        throw new UnrecognizedTagTypeException($tag);
                    }
                }
                if (strcasecmp($tag->getKey(), 'content') !== 0)
                {
                    continue;
                }
                $contentKey = $tag->getValue();
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
     * @return string
     */
    public function getTemplateModelExpression()
    {
        foreach ($this->elements as $element)
        {
            if ($element instanceof TagElement)
            {
                /** @var TagElement */
                $tagElement = $element;
                $tag = $tagElement->getTag();
                if (strcasecmp($tag->getKey(), 'templateModel') === 0)
                {
                    return $tag->getValue();
                }
            }
        }
        return '$' . $this->getModelName();
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

    /**
     * @param $newModelName
     */
    public function changeModelVariable($newModelName)
    {
        $this->elements = null;
        $this->hasTemplate = null;
        $this->content = null;
        $this->templateName = null;
        $this->code = str_replace(
            '$' . $this->getModelName(),
            '$' . $newModelName,
            $this->code);
        $this->modelName = $newModelName;
    }

    /**
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }
}