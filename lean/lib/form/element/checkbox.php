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
        if ($this->attribute('checked') === null) {
            if ($this->value() !== null && $this->value() == 1) {
                $this->attribute('checked', 'checked');
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
    public function value($value = null) {
        if (func_num_args() == 0) {
            return parent::value();
        }
        return parent::value(in_array($value, array('on', 1))
            ? 1
            : 0);
    }

    /**
     * @return Text
     */
    public function display() {
        printf('<input %1$s type="checkbox" name="%2$s" id="%2$s">', $this->getAttributeString(), $this->id());
        return $this;
    }
}