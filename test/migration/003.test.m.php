<?php
namespace test;

class DemoMigration003 implements \lean\Migration {
    public function up() {
        #\lean\Dump::flat('UP!');
    }
    public function down() {
        #\lean\Dump::flat('DOWN!');
    }
}

return new DemoMigration003();