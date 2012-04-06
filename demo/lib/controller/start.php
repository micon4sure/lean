<?php
namespace demo\controller;

class Start extends \lean\Controller {
    public function dispatch() {
        \lean\Dump::flat('/start;Start::dispatch', $this->getParams()->toArray());
    }

    public function fooAction() {
        \lean\Dump::flat('/start/foo;Start::fooAction', $this->getParams()->toArray());
    }
}