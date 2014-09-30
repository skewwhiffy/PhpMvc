<?php
namespace Framework\Templating\Html;

require_once 'IDocumentElement.php';
/**
 * Class HtmlElement
 * @package Framework\Templating\Html
 */
class HtmlElement implements IDocumentElement {
    /** @var string */
    private $code;

    /**
     * @param string $code
     */
    public function __construct($code){
        $this->code = $code;
    }

    /**
     * @returns string
     */
    public function getCode() {
        return $this->code;
    }
}