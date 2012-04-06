<?php
namespace lean\util;

class Object {
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
    public function __set($key, $value) {
        $this->data[$key] = $value;
    }

    public function __get($key) {
        return $this->data[$key];
    }
    public function __isset($key) {
        return isset($this->data[$key]);
    }
}