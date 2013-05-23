<?php
namespace lean\form\element;

/**
 * Form submit element
 */
class Submit extends \lean\form\Element {

    /**
     * @param $name  string
     * @param $label string
     */
    public function __construct($name, $label = '', $title = '') {
        parent::__construct($name, $label);
        $this->setAttribute('title', $title);
    }

    /**
     * @return Submit
     */
    public function display() {
        printf('<input %1$s type="submit" name="%2$s" id="%2$s" value="%3$s"/>', $this->getAttributeString(), $this->getId(), $this->getLabel());
        return $this;
    }
}