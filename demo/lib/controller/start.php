<?php
namespace demo\controller;

class Start extends \lean\Controller {

    public function dispatch($params) {
        \lean\Dump::flat('/start;Start::dispatch', $params->data());
    }

    public function fooAction($params) {
        \lean\Dump::flat('/start/foo;Start::fooAction', $params->data());
    }
}