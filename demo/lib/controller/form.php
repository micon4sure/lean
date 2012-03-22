<?php
namespace demo\controller;

class Form extends \lean\Controller {

    public function validationAction() {
        $form = new \lean\Form('test');
        $element = new \lean\form\element\Text('test');
        $form->addElement($element);

        $errors = array();

        \lean\Dump::flat('value:null, no validator', $form->isValid($errors), $element->isValid(), $errors);

        $element->addValidator(new \lean\form\Validator_Mandatory(array(\lean\form\Validator_Mandatory::ERR_NO_VALUE => 'No value set!')));
        \lean\Dump::flat('value:null, validator_mandatory', $form->isValid($errors), $element->isValid(), $errors);

        $form->populate(array($element->getId() => 'O HAI!'));
        \lean\Dump::flat('value: O HAI!, validator_mandatory', $form->isValid($errors), $element->isValid(), $errors);
    }
}