<?php
namespace demo\controller;
class Misc extends \lean\Controller {

    public function utilArrayObjectAction() {
        $this->getApplication()->slim()->request()->headers('Content-Type', 'text/plain');
        $object = new \lean\Util_ArrayObject(array('foo' => 'bar', 'data' => 'asd'));
        printf("%s\n%s", $object->foo, $object->data);
    }

    public function dumpAction() {
        $foo = new Misc_Foo;

        $prototype = \lean\Dump::create()
            ->methods(false)
            ->levels(1)
            ->magic(false)
            ->sort(false)
            ->wrap(false)
            ->flush(false);
        \lean\Dump::prototype($prototype);

        echo '<pre>';
        \lean\Dump::flat($foo);
        echo '</pre>';

        \lean\Dump::create()
            ->methods(true)
            ->levels(2)
            ->magic(true)
            ->sort(true)
            ->wrap(true)
            ->goes($foo);

        \lean\Dump::prototype()->wrap(true);
        \lean\Dump::deep(3, $foo);

    }
}


class Misc_Foo {

    public $foo = 'bar';

    protected $bar = array('foo' => 'bar', 'qux' => array('kos', 'asd'));

    private $qux = 'kos';

    public function foo() {

    }

    public static function sfoo() {

    }

    protected function bar() {

    }

    protected static function sbar() {

    }

    private function qux() {

    }

    private static function squx() {

    }

    public function __get($key) {

    }
}