<?php
namespace test;

class MigrationTest  extends \PHPUnit_Framework_TestCase {
    const DIR = './migration';
    const FILE = '/history.dat';

    /**
     * @var \lean\Migration_Manager
     */
    private static $manager;

    public static function setupBeforeClass() {
        self::$manager = new \lean\Migration_Manager(self::DIR);
        if(file_exists((self::DIR . self::FILE)))
            unlink(self::DIR . self::FILE);
    }

    public static function tearDownAfterClass() {
        unlink(self::DIR . self::FILE);
    }

    /**
     * Test if file is created if not present
     */
    public function testCreation() {
        self::$manager->init();
        $file = self::DIR . self::FILE;
        $this->assertTrue(file_exists($file));
    }

    public function testUpgrade() {
        self::$manager->upgrade(2);
        $this->assertEquals(self::$manager->getHistory(), array('001', '002'));

        self::$manager->downgrade(1);
        $this->assertEquals(self::$manager->getHistory(), array('001'));
   }
}