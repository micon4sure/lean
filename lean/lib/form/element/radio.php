<?php
namespace lean\form\element;

/**
 * Radio form element
 */
class Radio extends \lean\form\Element {
    private $options;

    /**
     * @param $name
     * @param array $options
     */
    public function __construct($name, $options) {
        parent::__construct($name);
        $this->options = $options;
    }

    /**
     * @return Text
     */
    public function display($key = null) {
        if ($key === null) {
            foreach ($this->options as $key => $option) {
                $this->display($key);
            }
            return $this;
        }
        printf('<input %1$s type="radio" name="%2$s" id="%2$s" value="%3$s" %4$s />', $this->getAttributeString(), $this->getId(), $key, $key == $this->getValue() ? ' checked="checked"' : '');
        return $this;
    }
}