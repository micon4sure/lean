<?php

// bootstrap
require_once realpath('../lean/init.php');
$autoload = new \lean\Autoload();
$autoload->loadLean();
$autoload->register('test', __DIR__ . '/lib');


class LeanSuite {
    public static function suite() {
        $suite = new \PHPUnit_Framework_TestSuite('lean');
        $suite->addTestSuite('test\UtilTest');
        $suite->addTestSuite('test\I18NTest');
        $suite->addTestSuite('test\TextTest');
        $suite->addTestSuite('test\EnvironmentTest');
        $suite->addTestSuite('test\MigrationTest');

        return $suite;
    }
}