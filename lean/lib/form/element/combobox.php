<?php
namespace lean\form\element;

/**
 * Select form element
 */ class Combobox extends \lean\form\Element {

    /**
     * @var array combobox options
     */
    private $options = array();

    /**
     * @param $name
     */
    public function __construct($name, array $options) {
        parent::__construct($name);
        $this->options = $options;
    }

    /**
     * @return string
     */
    private function getOptionString() {
        $string = '';
        foreach ($this->options as $key => $value) {
            $string .= sprintf('<option %s value="%s">%s</option>', $key == $this->value()
                ? 'selected="selected"'
                : '', $key, htmlspecialchars($value));
        }
        return $string;
    }

    public function options(array $options = null) {
        if ($options !== null) {
            $this->options = $options;
            return $this;
        }
        return $this->options;
    }

    /**
     * @return Combobox
     */
    public function display() {
        printf('<select %1$s name="%2$s" id="%2$s">%3$s</select>', $this->getAttributeString(), $this->id(), $this->getOptionString());
        return $this;
    }
}