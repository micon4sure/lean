<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

#header('Content-Type: text/plain');

define('APPLICATION_ROOT', realpath('.'));

include '../external/slim/Slim/Slim.php';

include '../../lean/lean/init.php';
$autoload = new \lean\Autoload();
$autoload->register('demo', __DIR__ . '/lib');

$application = new \lean\Application(array('debug' => true));
$application->registerControllerDefaultRoute('\\demo\\controller');
$application->run();
?>