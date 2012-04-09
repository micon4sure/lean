<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

define('APPLICATION_ROOT', realpath('../'));

include '../../external/slim/Slim/Slim.php';

include '../../lean/init.php';


$autoload = new \lean\Autoload();
$autoload->register('demo', APPLICATION_ROOT . '/lib');

$env = $_SERVER['SERVER_NAME'] == 'lean.kamundo.de' ? 'production' : 'development';
$application = new \lean\Application(new \lean\Environment(APPLICATION_ROOT . '/config/environment.ini', $env));

$application->slim()->get('/', function() use($application) {
    $application->slim()->redirect('/whatis');
});

$application->registerControllerDefaultRoute('\\demo\\controller');

$application->run();
?>