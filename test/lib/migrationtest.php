<?php
namespace test;

class MigrationTest  extends \PHPUnit_Framework_TestCase {
    const FILE = '../history.dat';

    public function tearDown() {
        unlink(self::FILE);
    }

    /**
     * Test if file is created if not present
     */
    public function testCreation() {
        new \lean\Migration(self::FILE);
        $this->assertTrue(file_exists(self::FILE));
        $this->assertEquals(json_decode(file_get_contents(self::FILE)), new \stdClass);;
    }
}