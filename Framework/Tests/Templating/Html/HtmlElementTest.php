<?php
require_once __DIR__.'/../../../Includes.php';

use Framework\Templating\Html\HtmlElement;

/**
 * Class HtmlElementTest
 * @package Framework\Tests\Templating\Html
 */
class HtmlElementTest extends PHPUnit_Framework_TestCase
{
    public function testPersistsText()
    {
        $code = 'Hello, how you my darling';
        $element = new HtmlElement($code);
        $this->assertEquals($code, $element->getCode());
    }
}