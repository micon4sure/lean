<?php
namespace lean\form\element;

/**
 * input[type="file"] form element
 */

class File extends \lean\form\Element {

    private $target;

    public function __construct($name, $label = '', $target) {
        parent::__construct($name, $label);
        $this->target = $target;
    }

    /**
     * @return Text
     */
    public function display() {
        printf('<input %1$s type="file" name="%2$s" id="%2$s">', $this->getAttributeString(), $this->getId());
        return $this;
    }

    public function getTempName() {
        if(!array_key_exists($this->getId(), $_FILES)) {
            throw new \lean\Exception('No file has been uploaded, can not move.');
        }
        $file = $_FILES[$this->getId()];
        return $file['tmp_name'];
    }

    public function getOriginalName() {
        if(!array_key_exists($this->getId(), $_FILES)) {
            throw new \lean\Exception('No file has been uploaded, can not move.');
        }
        $file = $_FILES[$this->getId()];
        return $file['name'];
    }

    public function move($filename) {
        $tempName = $this->getTempName();

        $destination = APPLICATION_ROOT . $this->target . '/' . $filename;
        move_uploaded_file($tempName, $destination);
        return $destination;
    }
}