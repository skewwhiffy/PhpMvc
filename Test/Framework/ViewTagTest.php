<?php
include '../Framework/ViewTag.php';
include '../vendor/autoload.php';

class ViewTagTest extends PHPUnit_Framework_TestCase {
    public function testCanConstruct() {
        $open = '<@';
        $close = '@>';
        $key = 'key';
        $value = 'value';
        $raw = "$open $key=$value $close";
        $code = "before $raw after";
        $tag = new ViewTag($open, $close, 0, $code);
        $this->assertEquals($key, $tag->key);
    }
}
