<?php
include './init.php';
$application = new \lean\Application('demo\\controller', array('debug' => true));
$application->registerRoute('/start(/:bleh)', array('controller' => 'Start'));
$application->registerRoute('/', array('controller' => 'Start'));
$application->registerControllerDefaultRoute();

#\lean\Dump::prototype(\lean\Dump::create()->flush());

// before output
$application->slim()->hook('lean.application.before.dispatch', function() {
    ?>
<!DOCTYPE html>
<html>
    <head>
        <style type="text/css">
            #container {
                background: #fafafa;
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <h1>lean DEMO</h1>

            <h2>Dispatching</h2>
            <ul>
                <li><a href="/start">Start::dispatch</a></li>
                <li><a href="/start/foo">Start::fooAction</a></li>
                <li><a href="/dynamic">Dynamic::dispatch</a></li>
                <li><a href="/dynamic/foo/bar/qux">Dynamic::FooAction</a></li>
            </ul>
            <h2>Misc</h2>
            <ul>
                <li><a href="/misc/util-array-object">Misc::utilArrayObjectAction</a></li>
                <li><a href="/misc/dump">Misc::dumpAction</a></li>
            </ul>
            <h2>I18N</h2>
            <ul>
                <li><a href="/i18n">I18n::dispatch</a></li>
            </ul>
        </div>
        <div id="action">
    <?
});

// after output
$application->slim()->hook('lean.application.after.dispatch', function() {
    ?>
        </div>
    </body>
</html>
<?
});

$application->run();
?>