<?php
namespace test;
use \lean\Text as Text;

class TextTest extends \PHPUnit_Framework_TestCase {

    public function testObject() {
        $this->assertEquals('foo', Text::left('foobar', 3));
        $this->assertEquals('foo', Text::left('foobar', 'foo'));

        $this->assertEquals('bar', Text::mid('foobarqux', 3, 3));
        $this->assertEquals('bar', Text::mid('foobarqux', 'foo', 'bar'));

        $this->assertEquals('bar', Text::right('foobar', 3));
        $this->assertEquals('bar', Text::right('foobar', 'bar'));

        $this->assertEquals('bar', Text::offsetLeft('foobar', 3));
        $this->assertEquals('bar', Text::offsetLeft('foobar', 'foo'));

        $this->assertEquals('foo', Text::offsetRight('foobar', 3));
        $this->assertEquals('foo', Text::offsetRight('foobar', 'bar'));

        $this->assertEquals(3, Text::len('foo'));
        $this->assertEquals(4, Text::len('über'));
        $this->assertEquals(4, Text::len('über', 'UTF-8'));

        $this->assertEquals('foo-bar-qux', Text::splitCamelCase('FooBarQux'));
        $this->assertEquals('foo-bar-qux', Text::splitCamelCase('fooBarQux'));

        $this->assertEquals('fooBarQux', Text::toCamelCase('foo-bar-qux'));
        $this->assertEquals('FooBarQux', Text::toCamelCase('foo-bar-qux', true));

        $this->assertEquals('foo="bar" qux="baz" asd="kos"', Text::createAttributeString(array(
            'foo' => 'bar',
            'qux' => 'baz',
            'asd' => 'kos'
        )));
    }
}