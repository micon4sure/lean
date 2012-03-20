<?php
error_reporting(E_ALL | E_STRICT);

ini_set('display_errors', 1);

define('APPLICATION_ROOT', realpath('.'));

include '../../Slim/Slim/Slim.php';

include '../../lean/lean/init.php';
$autoload = new \lean\Autoload();
$autoload->register('demo', __DIR__ . '/lib');