<?php
namespace lean\util;

/**
 * Wrapper class for easyfing the process of creating anonmyous objects.
 */
class Object implements \IteratorAggregate {

    /**
     * @var array
     */
    private $leanInternalData;

    /**
     * Must be associative array
     *
     * @param array $data
     */
    public function __construct($data = array()) {
        $this->leanInternalData = $data;
    }

    /**
     * @return array
     */
    public function toArray() {
        return $this->leanInternalData;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key) {
        return array_key_exists($key, $this->leanInternalData);
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value) {
        $this->leanInternalData[$key] = $value;
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value) {
        $this->set($key, $value);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key) {
        return $this->leanInternalData[$key];
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key) {
        return $this->get($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public function __isset($key) {
        return isset($this->leanInternalData[$key]);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->leanInternalData);
    }
}