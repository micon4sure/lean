<?php
class LeanSuite {
    public static function suite() {
        $suite = new \PHPUnit_Framework_TestSuite('lean');
        require_once(__DIR__ . '/lean/test.php');
        $suite->addTestSuite('UtilTest');
        return $suite;
    }
}