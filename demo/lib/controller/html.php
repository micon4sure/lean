<?php
namespace demo\controller;

class Html extends \lean\controller\HTML {

    public function init() {
        parent::init();
        $this->getDocument()->addCSSheet('/css/fonts.css');
        $this->getDocument()->addLESSheet('/less/global.less');
        $this->getDocument()->addScript('/js/less.js', true);
        $this->getDocument()->addScript('/js/jquery.js', true);
    }

    public function dispatch() {
        $this->display();
    }
}