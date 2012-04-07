<?php
namespace demo\controller;

class Whatis extends HTML {
    public function dispatch() {
        $this->getDocument()->addLESSheet('/less/whatis.less');
        $this->display();
    }

    public function fooAction() {
    }
}