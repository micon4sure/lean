<?php
namespace lean\form\element;

/**
 * Form submit element
 */
class Button extends \lean\form\Element {

    /**
     * @var the value attribute of the submit
     */
    private $label;

    /**
     * @param $name  string
     * @param $label string
     */
    public function __construct($name, $label) {
        parent::__construct($name);
        $this->label = $label;
    }

    /**
     * @return Submit
     */
    public function display() {
        printf('<button %1$s name="%2$s" id="%2$s" title="%3$s">%3$s</button>', $this->getAttributeString(), $this->getId(), $this->label);
        return $this;
    }
}