<?php
namespace lean;

class Session {

    /**
     * @var null
     */
    private $link = null;

    public function __construct($namespace = null) {
        $this->startSession();
        if(!isset($_SESSION[$namespace]))
            $_SESSION[$namespace] = array();
        $this->link =& $_SESSION[$namespace];
    }

    /**
     * Set a session value
     *
     * @magic
     * @param $key   String
     * @param $value String
     */
    public function __set($key, $value) {
        $this->set($key, $value);
    }

    /**
     * @param String $key
     * @param        $value String
     * @internal param String $key
     */
    public function set($key, $value) {
        $this->link[$key] = $value;
    }

    /**
     * Return a session value
     *
     * @magic
     * @param $key String|null
     * @return mixed
     */
    public function __get($key) {
        return $this->get($key);
    }

    /**
     * Return a session value
     *
     * @param $key String|null
     * @return mixed
     */
    public function get($key) {
        return isset($this->link[$key])
            ? $this->link[$key]
            : null;
    }

    /**
     * Unset a session value
     *
     * @magic
     * @param $key
     * @internal param null|String $name
     */
    public function __unset($key) {
        if (!isset($this->link[$key])) {
            return;
        }
        unset($this->link[$key]);
    }

    public function __isset($key) {
        return isset($this->link[$key]);
    }

    /**
     * Start php session if not started already
     */
    private function startSession() {
        if (session_id() === '') {
            session_start();
        }
    }
}