<?php
namespace lean\form;

interface Validator {

    /**
     * @abstract
     * @param mixed  $value
     * @param array  $messages
     * @internal param string $message
     * @return boolean
     */
    public function isValid($value, &$messages = array());
}

abstract class Validator_Abstract implements Validator {
    private $messages;
    public function __construct($messages = array()) {
        $this->messages = $messages;
    }

    public function getErrorMessage($error) {
        if(!array_key_exists($error, $this->messages))
            throw new \lean\Exception("Error message not found for key '$error'");
        return $this->messages[$error];
    }
}

class Validator_Mandatory extends Validator_Abstract {

    const ERR_NO_VALUE = 'no_value_set';

    public function __construct($msg = '') {
        parent::__construct(array(self::ERR_NO_VALUE => $msg));
    }

    /**
     * @param mixed  $value
     * @param string $message
     * @return boolean
     */
    public function isValid($value, &$messages = array()) {
        if($value === null || is_string($value) && !strlen($value)) {
            $messages[] = $this->getErrorMessage(self::ERR_NO_VALUE);
            return false;
        }

        return true;
    }
}