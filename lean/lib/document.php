<?php
namespace lean;

class Document extends Template {
    public function __construct($file) {
        parent::__construct($file);
        $this->set('styles', array());
        $this->set('scripts', array());
    }

    public function addStyle($style) {
        $this->styles[] = $style;
    }

    public function addScript($script) {
        $this->scripts[] = $script;
    }
}