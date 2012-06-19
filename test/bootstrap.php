<?php
require_once realpath('../lean/init.php');
$autoload = new \lean\Autoload();
$autoload->loadLean();
$autoload->register('test', __DIR__ . '/lib');

define('STACK_APPLICATION_ROOT', __DIR__);