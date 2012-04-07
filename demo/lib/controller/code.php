<?php
namespace demo\controller;

class Code extends HTML {
    public function init() {
        parent::init();
        $this->getDocument()->addLESSheet('/less/code.less');
    }
    public function dispatch() {
        $this->redirect($this->getSlim()->urlFor(\lean\Application::DEFAULT_ROUTE_NAME, array('controller' => 'code', 'action' => 'start')));
    }
    public function controlAction() {
        $this->defaultAction();
    }
    public function startAction() {
        $this->defaultAction();
    }
    public function dumpAction() {
        $this->defaultAction();
    }
    public function defaultAction() {
        $this->addPartial(new \demo\partial\CodeNavigation('navigation', $this->getApplication()));
        $this->display();
    }
}