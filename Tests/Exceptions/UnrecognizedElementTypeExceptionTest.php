<?php
use Framework\Exceptions\UnrecognizedElementTypeException;
use Framework\Templating\Html\IDocumentElement;

require_once __DIR__ . '/../Includes.php';

/**
 * Class UnrecognizedElementTypeExceptionTest
 */
class UnrecognizedElementTypeExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testWorksWithClassType()
    {
        /** @var IDocumentElement $that */
        $that = $this;
        $exception = new UnrecognizedElementTypeException($that);
        $message = $exception->getMessage();

        $this->assertThat($message, $this->stringContains(__CLASS__));
    }
}