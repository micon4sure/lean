<?php
namespace demo\controller;

class Html extends \lean\controller\HTML {

    public function init() {
        parent::init();
        $this->getDocument()->addCSSheet('/css/fonts.css');
        $this->getDocument()->addLESSheet('/less/global.less');
        $this->getDocument()->addScript('/js/less.js', true);
        $this->getDocument()->addScript('/js/jquery.js', true);
        $this->getDocument()->addScript('/js/global.js', true);
        $this->getDocument()->addCSSheet('http://fonts.googleapis.com/css?family=Poiret+One|Open+Sans:400');
    }

    public function dispatch() {
        $this->display();
    }
}