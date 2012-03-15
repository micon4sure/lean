<?php
namespace demo\controller;

class I18n extends \lean\Controller {

    public function dispatch() {
        $i18n = new \lean\I18N_Resolve('./', 'en');
        ?>
    <h3>Default locale en</h3>
    <div>
        Resolving 'hello': <?= $i18n->resolve('hello') ?><br/>
        Resolving 'hello_name' with parameter 'Master Splinter': <?= $i18n->resolve('hello_name', 'Master Splinter') ?>
    </div>

    <h3>Pushing locale 'de'</h3>
    <? $i18n->pushLocale('de'); ?>
    <div>
        Resolving 'hello': <?= $i18n->resolve('hello') ?><br/>
        Resolving 'hello_name' with parameter 'Meister Splinter': <?= $i18n->resolve('hello_name', 'Meister Splinter') ?>
    </div>

    <h3>Popping locale</h3>
    <? $i18n->pushLocale('de'); ?>
    <div>
        Resolving 'hello': <?= $i18n->resolve('hello') ?><br/>
        Resolving 'hello_name' with parameter 'Master Splinter': <?= $i18n->resolve('hello_name', 'Master Splinter') ?>
    </div>
    <?
    }
}