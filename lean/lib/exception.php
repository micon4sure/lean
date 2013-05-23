<?php

namespace lean;

/**
 * lean exception
 */
class Exception extends \Exception {

}

class Exception_Forward extends Exception {
    private $destination;

    /**
     * @param string $destination
     */
    public function __construct($destination) {
        parent::__construct("Forwarding to action '$destination'");
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function getDestination() {
        return $this->destination;
    }
}

class Exception_Template_TemplatePathNotFound extends Exception {

}

class Exception_UnknownControllerClass extends Exception {}
class Exception_UnknownControllerAction extends Exception {}