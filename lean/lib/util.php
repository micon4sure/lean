<?php
namespace lean;

class Util_ArrayObjectBase {

    private $data;

    public function __construct($data = array()) {
        $this->data = $data;
    }

    public function data($key = null, $value = null) {
        if (func_num_args() == 0) {
            return $this->data;
        }
        if (func_num_args() == 1) {
            return $this->data[$key];
        }
        $this->data[$key] = $value;
        return $this;
    }

    public function has($key) {
        return array_key_exists($key, $this->data);
    }
}


class Util_ArrayObject extends Util_ArrayObjectBase {

    public function __set($key, $value) {
        return $this->data($key, $value);
    }

    public function __get($key) {
        return $this->data($key);
    }
}