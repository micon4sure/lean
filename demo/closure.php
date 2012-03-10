<?php
namespace closure;

class Foo {
    private $closures = array();

    public function test($param) {
        $closure = $this->closures[] = function() use($param) {
            echo $param;
        };
        $closure->bindTo($this);
    }

    public function run() {
        foreach($this->closures as $closure) {
            $closure();
            call_user_func($closure);
            call_user_func_array($closure, array());
            echo ' ';
        }
    }
}
$foo = new Foo;
$foo->test('foo');
$foo->test('bar');
$foo->run();