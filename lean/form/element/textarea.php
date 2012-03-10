<?php
namespace lean\form\element;

/**
 * textarea form element
 */ class Textarea extends \lean\form\Element {
    /**
     * @return Textarea
     */
    public function display() {
        printf('<textarea %1$s name="%2$s" id="%2$s">%3$s</textarea>', $this->getAttributeString(), $this->id(), htmlspecialchars($this->value()));
        return $this;
    }
}