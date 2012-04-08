<h2>code</h2>
<? $this->navigation() ?>

<h3>dumping data with lean!</h3>
    <h4>Without lean, the life of a PHP developer can be a mess, as shown here:</h4>
<p>
    While there are debugging tools for php, it's often easiest and fastest for a developer to just dump the data they
    have
    been given onto the website.
    Consider this code:
</p>
<? $sample = new \demo\Sample(); ?>
<? $code = file_get_contents(APPLICATION_ROOT . '/lib/sample.php') . <<<ENDCODE

\$sample = new \demo\Sample;

ENDCODE;
highlight_string($code); ?>
<p>
    Now. Let's say you have such an object in your template and you want to know what's in there and what methods it
    has.
</p>
<p>One way to do this would be</p>
<? $code = <<<ENDCODE
<pre>
<?
print_r(\$sample);
// or
var_dump(\$sample);
// and
print_r(get_class_methods(\$sample));
?>
</pre>
ENDCODE;
highlight_string($code); ?>
<p>Resulting in output like this:</p>

<pre>
<?
    print_r($sample);
    // or
    var_dump($sample);
    // and
    print_r(get_class_methods($sample));
    ?>
</pre>
<p>
    All well and good, but it doesn't give you a good view of what you're dealing with. Especially annoying if the object is nested very deep.
<p/>
<h4>lean\Dump to the rescue!</h4>
<p>
    the Dump class has a lot of configuration options, making it easy for you to examine the data at hand, look:
</p>
<? // FLUSHING ?>
<? $code = <<<ENDCODE
<? \lean\Dump::prototype(\lean\Dump::create()->flush(false)); // do not flush the output buffer by default ?>
ENDCODE;
highlight_string($code); ?>
<? \lean\Dump::prototype(\lean\Dump::create()->flush(false)); // do not flush the output buffer by default ?>

<? // FLAT ?>
<? $code = <<<ENDCODE
<? \lean\Dump::flat(\$sample); // one level ?>
ENDCODE;
highlight_string($code); ?>
<? \lean\Dump::flat($sample); // one level ?>

<? // DEEP ?>
<? $code = <<<ENDCODE
<? \lean\Dump::deep(2, \$sample); // three levels ?>
ENDCODE;
highlight_string($code); ?>
<? \lean\Dump::deep(3, $sample); // three levels ?>

<? // CUSTOM ?>
<? $code = <<<ENDCODE
<?
// dump two levels without showing methods or string representation
\lean\Dump::create(2)->methods(false)->showString(false)->goes(\$sample);
?>
ENDCODE;
highlight_string($code); ?>
<?
// dump two levels without showing methods or string representation
\lean\Dump::create(2)->methods(false)->showString(false)->goes($sample);
?>
<p>
    Did you see that? It even shows you where your dump is coming from!
</p>