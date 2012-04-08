<?php
namespace test;

class I18NTest extends \PHPUnit_Framework_TestCase {

    private $l10n = array();

    public function setUp() {
        $this->l10n['en'] = require './l10n/en.php';
        $this->l10n['de'] = require './l10n/de.php';
    }

    public function testEnglish() {
        $i18n = new \lean\I18N('./l10n', 'en');
        $this->assertEquals($this->l10n['en']['hello'], $i18n->resolve('hello'));
        $this->assertEquals(sprintf($this->l10n['en']['hello_name'], 'frodo'), $i18n->resolve('hello_name', 'frodo'));
    }

    public function testLocaleStack() {
        $i18n = new \lean\I18N('./l10n', 'en');
        $i18n->pushLocale('de');
        $this->assertEquals($this->l10n['de']['hello'], $i18n->resolve('hello'));
        $i18n->popLocale();
        $this->assertEquals($this->l10n['en']['hello'], $i18n->resolve('hello'));
    }

    public function testUnresolvedCallback() {
        $i18n = new \lean\I18N('./l10n', 'en');
        $executed = false;
        $i18n->setUnresolvedCallback(function() use(&$executed) {
            $executed = true;
        });
        $i18n->resolve('missing');
        $this->assertTrue($executed);
    }
}