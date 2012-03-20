<?php

namespace lean;

/**
 * Template base holds all the data
 */
class Template_Base {

    /**
     * @var the template files
     */
    private $file;

    /**
     * @var array the data to be used in the template
     */
    private $data = array();

    /**
     * @var array callbacks for inside the template
     */
    private $callbacks = array();

    /**
     * @param      $file string
     *
     * @throws Exception_Template_TemplatePathNotFound
     */
    public function __construct($file) {
        $this->file = $file;

        if (!file_exists($this->file)) {
            throw new Exception_Template_TemplatePathNotFound("Template file does not exist: '{$this->file}'");
        }
    }

    /**
     * @return string the template file
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Set a temple variable
     *
     * @param $key   string
     * @param $value mixed
     *
     * @return Template_Base
     */
    public function set($key, $value) {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @param array $params
     * @return \lean\Template_Base
     */
    public function setData(array $data) {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Get a template variable
     *
     * @param $key string
     *
     * @return mixed
     */
    public function get($key) {
        return $this->data[$key];
    }

    /**
     * Call back
     *
     * @param $name string
     * @param $args mixed
     *
     * @return mixed
     */
    public function call($name, $args) {
        $callback = $this->getCallback($name);
        return call_user_func_array($callback, $args);
    }

    /**
     * @param $name
     * @param $callback
     * @return Template_Base
     * @throws Exception
     */
    public function setCallback($name, $callback) {
        // set callback
        if (in_array($name, get_class_methods($this))) {
            throw new Exception("'$name' can not be a callback, restricted.");
        }
        $this->callbacks[$name] = $callback;
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public function getCallback($name) {
        if (!isset($this->callbacks[$name])) {
            throw new Exception("Callback '$name' is not registered.");
        }
        return $this->callbacks[$name];
    }

    /**
     * Display the template
     *
     * @return Template
     */
    public function display() {
        include $this->getFile();
        return $this;
    }

    /**
     * Return the template display output
     *
     * @return Template
     */
    public function render() {
        ob_start();
        $this->display();
        return ob_get_clean();
    }
}


/**
 * Magic wrapper for Template_Base
 */
class Template extends Template_Base {

    /**
     * @param $key   string
     * @param $value mixed
     *
     * @magic set a template variable via magic
     */
    public function __set($key, $value) {
        $this->set($key, $value);
    }

    /**
     * @param $key string
     *
     * @magic get a template variable via magic
     * @return mixed
     */
    public function __get($key) {
        return $this->get($key);
    }

    /**
     * @param $name string
     * @param $args array
     *
     * @magic call back
     * @return mixed
     */
    public function __call($name, array $args) {
        $callback = parent::getCallback($name);
        return call_user_func_array($callback, $args);
    }

    /**
     * Display the template
     *
     * @return Template
     */
    public function display() {
        include $this->getFile();
        return $this;
    }
}