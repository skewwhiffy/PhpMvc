<?php
use Framework\Common\StringExtensions;

require_once __DIR__ . '/../../Framework/Includes.php';

/**
 * Class StringExtensionsTest
 */
class StringExtensionsTest extends PHPUnit_Framework_TestCase
{
    /** @var StringExtensions */
    private $extensions;

    public function setUp()
    {
        $this->extensions = new StringExtensions();
    }

    public function testEndsWithWorksForFalse()
    {
        $source = 'thisDoesNotEndWithWhatIAmLookingFor';
        $ending = 'ending';

        $result = $this->extensions->endsWith($source, $ending);

        $this->assertThat($result, $this->isFalse());
    }

    public function testEndsWithWorksForTrue(){
        $source = 'thisEndsWithEnding';
        $ending = 'Ending';

        $result = $this->extensions->endsWith($source, $ending);

        $this->assertThat($result, $this->isTrue());
    }

    public function testEndsWithWorksForEmptyEnding(){
        $source = 'thisIsNotEmpty';
        $ending = null;

        $result = $this->extensions->endsWith($source, $ending);

        $this->assertThat($result, $this->isTrue());
    }

    public function testEndsWithWorksForEmptySource(){
        $source = null;
        $ending = 'notEmpty';

        $result = $this->extensions->endsWith($source, $ending);

        $this->assertThat($result, $this->isFalse());
    }
}