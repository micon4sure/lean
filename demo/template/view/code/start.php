<h2>code</h2>
<? $this->navigation() ?>

<h3>gentlemen, start your engines!</h3>
<p>
    Ok, so you read all about what lean is and what it does and now you're eager to see some code! Buckle up then, here
    goes!
</p>
<? $code = <<<ENDCODE
<?php
// fire up the autoloader and register your lib
\$autoload = new \lean\Autoload();
\$autoload->register('demo', APPLICATION_ROOT . '/lib');

// instantiate the application
\$application = new \lean\Application(array('debug' => true));

// register a route
\$application->registerRoute('demo', '/demo/:id', array('controller' => '\lean\demo\Awesome', action => 'sweet'));

// aaaaaand… go!
\$application->run();

ENDCODE;
highlight_string($code); ?>

<p>
    Accessing <em>/demo/23</em> will now call the <em>sweetAction</em> in <em>\lean\demo\Awesome</em> with the id parameter set to 23!
</p>