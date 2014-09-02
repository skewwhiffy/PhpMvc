<?php
class ViewTagTest extends PHPUnit_Framework_TestCase {
    public function testFalse() {
        $this -> assertEquals(1, 0);
    }
    public function testTrue() {
        $this -> assertEquals(1, 0 + 1);
    }
}
