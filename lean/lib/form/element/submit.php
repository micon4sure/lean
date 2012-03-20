<?php
namespace lean\form\element;

/**
 * Form submit element
 */ class Submit extends \lean\form\Element {

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
        printf('<input %1$s type="submit" name="%2$s" id="%2$s" value="%3$s" title="%3$s">', $this->getAttributeString(), $this->id(), $this->label);
        return $this;
    }
}