<?php
class LeanSuite {
    public static function suite() {
        $suite = new \PHPUnit_Framework_TestSuite('lean');
        $suite->addTestSuite('test\UtilTest');
        $suite->addTestSuite('test\I18NTest');
        $suite->addTestSuite('test\TextTest');
        $suite->addTestSuite('test\EnvironmentTest');
        return $suite;
    }
}