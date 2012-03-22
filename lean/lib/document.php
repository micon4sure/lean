<?php
namespace lean;

class Document extends Template {


    public function __construct($file) {
        parent::__construct($file);
        $this->set('styles', array());
        $this->set('scripts', array());
    }

    public function addStyle($style) {
        $styles = $this->styles;
        $styles[] = $style;
        $this->set('styles', $styles);
    }

    public function addScript($script) {
        // get unique key
        $key = md5($script);

        // if script already exists ignore
        if(array_key_exists($key, $this->scripts))
            return;
        $scripts = $this->scripts;
        $scripts[$key] = $script;
        $this->set('scripts', $scripts);
    }

    public function setTitle($title) {
        $this->set('title', $title);
    }
}