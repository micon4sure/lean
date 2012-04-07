<?php
namespace demo\controller;

class Html extends \lean\controller\HTML {

    public function init() {
        parent::init();
        $this->getDocument()->addLessSheet('/less/fonts.less');
        $this->getDocument()->addLessSheet('/less/global.less');
        $this->getDocument()->addScript('/js/less.js', true);
    }

    public function dispatch() {
        $this->display();
    }
}