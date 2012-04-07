<h2>code</h2>
<? $this->navigation() ?>

<h3>gentlemen, start your engines!</h3>
<p>
    Ok, so you read all about what lean is and what it does and now you're eager to see some code! Buckle up then, here
    goes!
</p>
<? $code = <<<ENDCODE
<?php
// define your application path, usually one directory level above your public html folder
define('APPLICATION_ROOT', realpath('../'));

// get slim rolling
include '/path/to/lean/external/slim/Slim/Slim.php';

// get lean itself rolling
include '/path/to/lean/lean/init.php';

// fire up the autoloader and register your lib
\$autoload = new \lean\Autoload();
\$autoload->register('demo', APPLICATION_ROOT . '/lib');

// instantiate the application
\$application = new \lean\Application(array('debug' => true));

// register the controller default route /:controller/:action(/additional/parameters)
\$application->registerControllerDefaultRoute('\\demo\\controller');

// aaaaaand… go!
\$application->run();

ENDCODE;
highlight_string($code); ?>