<?php
namespace demo\controller;

class Html extends \lean\controller\HTML {

    public function init() {
        parent::init();
        $this->getDocument()->addCSSheet('http://fonts.googleapis.com/css?family=Poiret+One|Open+Sans:400');
        $this->getDocument()->addLESSheet('/less/global.less');

        $this->getDocument()->addScript('/js/less.min.js');
        $this->getDocument()->addScript('/js/less.min.js');
        $this->getDocument()->addScript('/js/jquery.min.js');
        $this->getDocument()->addScript('/js/global.js');
    }

    public function dispatch() {
        $this->display();
    }
}