<?php
class UtilTest extends PHPUnit_Framework_TestCase {

    public function testObject() {
        $object = new \lean\util\Object();

        $object->foo = 'bar';

        $this->assertEquals($object->foo, 'bar');

        $this->assertTrue(isset($object->foo));
        $this->assertTrue($object->has('foo'));

        $this->assertFalse(isset($object->bar));
        $this->assertFalse($object->has('bar'));
    }

}