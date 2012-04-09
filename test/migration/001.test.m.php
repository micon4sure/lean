<?php
namespace test;

class DemoMigration001 implements \lean\Migration {
    public function up() {
        #\lean\Dump::flat('UP!');
    }
    public function down() {
        #\lean\Dump::flat('DOWN!');
    }
}

return new DemoMigration001();