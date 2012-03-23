<?php
namespace lean\form\element;

/**
 * input[type="text"] form element
 */ class Password extends Text {

    /**
     * @return Text
     */
    public function display() {
        printf('<input %1$s type="password" name="%2$s" id="%2$s">', $this->getAttributeString(), $this->getId());
        return $this;
    }
}