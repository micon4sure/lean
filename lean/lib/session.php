<?php
namespace lean;

class Session {
    const NAMESPACE_SEPERATOR = '\\';

    private $namespace = '';
    private $hasNamespace = false;

    public function __construct($namespace=null) {
        $this->startSession();
        $this->setNamespace($namespace);
    }

    /**
     * Set a namespace
     * @param null $namespace
     */
    public function setNamespace($namespace=null) {
        if($namespace === null) {
            $this->hasNamespace = false;
        } else {
            $this->hasNamespace = true;
            $this->namespace = $namespace;
        }
    }

    /**
     * Set a session value
     * @magic
     * @param $name String
     * @param $value String
     */
    public function __set($name, $value) {
        $_SESSION[$this->buildSessionKey($name)] = $value;
    }

    /**
     * Return a session value
     * @magic
     * @param $name String|null
     */
    public function __get($name) {
        $key = $this->buildSessionKey($name);
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * Unset a session value
     * @magic
     * @param $name String|null
     */
    public function __unset($name) {
        $key = $this->buildSessionKey($name);
        if(!isset($_SESSION[$key]))
            return;
        unset($_SESSION[$key]);
    }

    /**
     * Build valid session array key for namespace access
     * @param $key
     * @return string
     */
    private function buildSessionKey($key) {
        if(!$this->hasNamespace) {
            return $key;
        }
        return $this->namespace . static::NAMESPACE_SEPERATOR . $key;
    }

    /**
     * Start php session if not started already
     */
    private function startSession() {
        if(session_id() === '') {
            session_start();
        }
    }
}