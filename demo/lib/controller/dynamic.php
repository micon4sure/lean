<?php
namespace demo\controller;

class dynamic extends \lean\Controller {

    public function dispatch() {
        \lean\Dump::flat('/dynamic;Dynamic::dispatch', $this->getParams()->data());

    }

    public function fooAction() {
        \lean\Dump::flat('/dynamic/foo/bar/qux;Dynamic::fooAction', $this->getParams()->data());
    }
}