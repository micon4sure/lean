<h2>code</h2>
<? $this->navigation() ?>
<h3>get the controller going</h3>
<p>
    We defined our demo lib earlier, let's say we're on /foo/bar.
    That would be the controller 'demo\controller\Foo' and the action 'barAction'.
    Since we registered our demo lib to sit at APPLICATION_ROOT . '/lib', the controller needs to be at APPLICATION_ROOT
    . '/lib/controller/foo.php';<br/>
    This is what it could look like:
</p>
    <? $code = <<<ENDCODE
<?php
namespace demo\controller;

class Foo extends \lean\controller\HTML {
    public function barAction() {
        echo "O HAI!";
    }
}


ENDCODE;
highlight_string($code); ?>

<h3>sooooo... templates?!</h3>
<p>
    Yes. Of course there are templates. Here's how they roll:
</p>

<div id="code_control_document">
    The outer layer is the <strong>document</strong>. It handles stuff like the head and title tags.
    <div id="code_control_layout">
        One layer below is the <strong>layout</strong>. You should have stuff here like the header, your navigation and footer.
        <div id="code_control_view">
            The most inner layer is the actual <strong>view</strong>.
        </div>
    </div>
</div>
<p>And this is what your barAction may look like, making use of the view templating</p>
    <? $code = <<<ENDCODE
<?php
public function barAction() {
    // template variables go in data
    \$this->data->characters = array('Tyrion', 'Robb');
    \$this->display();
}


ENDCODE;
highlight_string($code); ?>
<p>
    Now, all you need is a template at APPLICATION_ROOT . '/templates/views/foo/bar.php'<br/>
    It might look like this:
</p>
<? $code = <<<ENDCODE
<? foreach(\$this->characters as \$character): ?>
    <span class="character"><?= \$character ?></span>
<? endforeach; ?>
ENDCODE;
highlight_string($code); ?>
