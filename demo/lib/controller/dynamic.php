<?php
namespace demo\controller;

class dynamic extends \lean\Controller {

    public function dispatch($params) {
        \lean\Dump::flat('/dynamic;Dynamic::dispatch', $params->data());

    }

    public function fooAction($params) {
        \lean\Dump::flat('/dynamic/foo/bar/qux;Dynamic::fooAction', $params->data());
    }
}