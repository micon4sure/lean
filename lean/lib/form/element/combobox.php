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
     * @param string $name
     * @param string $title
     * @param array  $options
     */
    public function __construct($name, $title, array $options) {
        parent::__construct($name, $title);
        $this->options = $options;
    }

    /**
     * @return string
     */
    private function getOptionString() {
        $string = '';
        foreach ($this->options as $key => $value) {
            $string .= sprintf('<option %s value="%s">%s</option>', $key == $this->getValue()
                ? 'selected="selected"'
                : '', $key, htmlspecialchars($value));
        }
        return $string;
    }

    /**
     * @param array|null $options
     * @return array|Combobox
     */
    public function getOptions(array $options = null) {
        if ($options !== null) {
            $this->options = $options;
            return $this;
        }
        return $this->options;
    }

    /**
     * Get the label for the currently active value
     */
    public function getLabelForValue() {
        return $this->options[$this->getValue()];
    }

    /**
     * @return Combobox
     */
    public function display() {
        printf('<select %1$s name="%2$s" id="%2$s">%3$s</select>', $this->getAttributeString(), $this->getId(), $this->getOptionString());
        return $this;
    }
}