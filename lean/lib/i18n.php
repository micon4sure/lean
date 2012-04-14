<?php
namespace lean;

class I18N {
    /**
     * @var Formatter
     */
    private $formatter;

    /**
     * @var Resolver
     */
    private $resolver;
    /**
     * @var array
     */
    private $stack = array();

    /**
     * @param string $directory
     * @param string $locale
     * @param string|\DateTimeZone $timezone
     */
    public function __construct($directory, $locale, $timezone = null) {
        if($timezone === null) {
            $timezone = date_default_timezone_get();
        }
        if(!$timezone instanceof \DateTimeZone) {
            $timezone = new \DateTimeZone($timezone);
        }

        $this->pushContext(new \lean\i18n\Context($locale, $timezone));
    }

    /**
     * Return lazy loaded formatter
     * @return i18n\Formatter
     */
    public function getFormatter() {
        return $this->formatter === null
            ? $this->formatter = new Formatter($this->getLocale(), $this->getTimezone())
            : $this->formatter;
    }

    /**
     * Return lazy loaded resolver
     * @return i18n\Resolver
     */
    public function getResolver() {
        return $this->resolver === null
            ? $this->resolver = new Resolver($this->getLocale(), $this->getTimezone())
            : $this->resolver;
    }

    /**
     * @param \lean\i18n\Context $context
     */
    public function pushContext(\lean\i18n\Context $context) {
        array_push($this->stack, $context);
    }

    /**
     * @return \lean\i18n\Context
     */
    public function popContext() {
        return array_pop($this->stack);
    }

    /**
     * @return \lean\i18n\Context
     */
    public function currentContext() {
        return end($this->stack);
    }

    /**
     * @param string $key
     * @return \lean\i18n\Localization
     * @throws \lean\Exception
     */
    public function localize($key) {
        if(!count($this->stack)) {
            throw new \lean\Exception('Context stack is empty. Push at least one context before trying to localize');
        }
        return new \lean\i18n\Localization($this->currentContext(), $this->getResolver()->resolve($key));
    }
}