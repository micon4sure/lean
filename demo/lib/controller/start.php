<?php
namespace demo\controller;

class Start extends \lean\controller\HTML {
    public function dispatch() {
        \lean\Dump::flat('/start;Start::dispatch', $this->getParams()->toArray());
        $this->display();
    }

    public function fooAction() {
        \lean\Dump::flat('/start/foo;Start::fooAction', $this->getParams()->toArray());
    }
}