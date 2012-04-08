<?php
namespace demo;

/**
 * Just a sample class with some random data and an empty function
 */
class Sample {
    private $data;

    public function __construct() {
        $object = new \stdClass;
        $object->array = array('foo', 'bar', null, true, false);
        $this->data = $object;
    }

    public function __toString() {
        return 'O HAI THER!';
    }
    public static function hello() {}
}