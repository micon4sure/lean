<?php
namespace test;

class EnvironmentTest extends \PHPUnit_Framework_TestCase {

    public function testSimpleEnvironment() {
        $environment = new \lean\Environment_Local('./config/environment.ini');
        $debug = $environment->get('debug');
        $this->assertFalse((bool)$debug);
        $this->assertEquals('barlocal', $environment->get('foo'));
    }

    public function testStages() {
        $production = new \lean\Environment('./config/environment.ini', 'production');
        $testing = new \lean\Environment('./config/environment.ini', 'testing');
        $development = new \lean\Environment('./config/environment.ini', 'development');

        $this->assertFalse((bool)$production->get('debug'));
        $this->assertTrue((bool)$testing->get('debug'));
        $this->assertTrue((bool)$development->get('debug'));
    }
}