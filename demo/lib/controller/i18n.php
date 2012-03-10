<?php
namespace demo\controller;

class I18n extends \lean\Controller {
    public function dispatch() {
        $i18n = new \lean\I18N('./', 'en');
        ?>
        Resolving foo: <?= $i18n->translate('foo'); ?><br/>
        Resolving qux with parameter kos: <?= $i18n->translate('qux', 'kos'); ?><br/>
        Resolving foo statically: <?= \lean\I18n::translate('foo'); ?><br/>
        Resolving foo via singleton: <?= \lean\I18n::instance()->resolve('foo'); ?><br/>
    <?
    }
}