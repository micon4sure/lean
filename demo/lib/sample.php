<?php
namespace demo;

class Sample {
    private $data;

    public function __construct() {
        $object = new \stdClass;
        $object->array = array('foo', 'bar', null, true, false);
        $this->data = $object;
    }

    public static function hello() {}
}