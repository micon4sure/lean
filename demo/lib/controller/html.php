<?php
namespace demo\controller;

class Html extends \lean\controller\HTML {
    public function dispatch() {
        $this->set('foo', 'bar');
        $this->display();
    }

    protected function getLayoutFile() {
        return APPLICATION_ROOT . '/layout.php';
    }
}