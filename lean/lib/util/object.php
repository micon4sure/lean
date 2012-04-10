<?php
namespace lean\util;
/**
 * TODO docs
 */
class Object implements \IteratorAggregate {
    private $data;

    public function __construct($data = array()) {
        $this->data = $data;
    }

    public function toArray() {
        return $this->data;
    }

    public function has($key) {
        return array_key_exists($key, $this->data);
    }
    public function set($key, $value) {
        $this->data[$key] = $value;
    }
    public function __set($key, $value) {
        $this->set($key, $value);
    }

    public function get($key) {
        return $this->data[$key];
    }
    public function __get($key) {
        return $this->get($key);
    }
    public function __isset($key) {
        return isset($this->data[$key]);
    }

    public function getIterator() {
        return new \ArrayIterator($this->data);
    }
}