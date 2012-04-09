<?php
namespace demo\partial;

class CodeNavigation extends \lean\Partial {
    public function init() {
        $this->data->elements = array(
            'start' => 'starting the engine',
            'control' => 'controlling the power',
            'dump' => 'making a dump',
            //'form' => 'netforming'
        );
    }
}