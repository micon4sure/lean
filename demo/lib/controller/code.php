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
    public function formAction() {
        $form = new \lean\Form('test');
        $form->addElement(new \lean\form\element\Text('test'));
        $form->addElement(new \lean\form\element\Submit('submit', 'Submit'));

        $errors = array();

        \lean\Dump::flat('value:null, no validator', $form->isValid($errors), $element->isValid(), $errors);

        $element->addValidator(new \lean\form\Validator_Mandatory(array(\lean\form\Validator_Mandatory::ERR_NO_VALUE => 'No value set!')));
    }
    public function defaultAction() {
        $this->addPartial(new \demo\partial\CodeNavigation('navigation', $this->getApplication()));
        $this->display();
    }
}