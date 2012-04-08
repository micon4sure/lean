<?php
class LeanSuite {
    public static function suite() {
        $suite = new \PHPUnit_Framework_TestSuite('lean');
        $suite->addTestSuite('test\UtilTest');
        return $suite;
    }
}