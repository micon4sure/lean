<?php
namespace test;

class EnvironmentTest extends \PHPUnit_Framework_TestCase {

    public function testStages() {
        $production = new \lean\application\Environment('./environment.ini', 'production');
        $testing = new \lean\application\Environment('./environment.ini', 'testing');
        $development = new \lean\application\Environment('./environment.ini', 'development');

        $this->assertTrue(!(bool)$production->get('debug'));
        $this->assertTrue((bool)$testing->get('debug'));
        $this->assertTrue((bool)$development->get('debug'));
    }

}