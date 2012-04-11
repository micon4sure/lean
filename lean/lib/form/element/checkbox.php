<?php
namespace lean\form\element;

/**
 * input[type="text"] form element
 */ class Checkbox extends \lean\form\Element {

    /**
     * Create a string of the attributes, fit to be display. e.g.: 'checked="checked" disabled="disabled"'
     * Includes the set css classes
     *
     * @return string
     */
    public function getAttributeString() {
        if ($this->getAttribute('checked') === null) {
            if ($this->getValue() !== null && $this->getValue() == 1) {
                $this->setAttribute('checked', 'checked');
            }
        }

        return parent::getAttributeString();
    }

    /**
     * Get or set the value
     * on results to 1
     *
     * @param null $value
     *
     * @return Element|string
     */
    public function setValue($value = null) {
        return parent::setValue(($value && in_array($value, array('on', 1)))
            ? 1
            : 0);
    }

    /**
     * @return Text
     */
    public function display() {
        printf('<input %1$s type="checkbox" name="%2$s" id="%2$s">', $this->getAttributeString(), $this->getId());
        return $this;
    }
}