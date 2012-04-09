<?php
namespace test;

class EnvironmentTest extends \PHPUnit_Framework_TestCase {

    public function testStages() {
        $production = new \lean\Environment('./environment.ini', 'production');
        $testing = new \lean\Environment('./environment.ini', 'testing');
        $development = new \lean\Environment('./environment.ini', 'development');

        $this->assertTrue(!(bool)$production->get('debug'));
        $this->assertTrue((bool)$testing->get('debug'));
        $this->assertTrue((bool)$development->get('debug'));
    }

}