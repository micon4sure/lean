<?php
namespace lean\i18n;

class Context {
    /**
     * @var string
     */
    private $locale;
    /**
     * @var \DateTimeZone
     */
    private $timezone;

    /**
     * @param string $locale
     * @param \DateTimeZone $timezone
     */
    public function __construct($locale, \DateTimeZone $timezone) {
        $this->locale = $locale;
        $this->timezone = $timezone;
    }

    /**
     * @return array|\Locale|string
     */
    public function getLocale() {
        return $this->locale;
    }
    /**
     * @return \DateTimeZone|string
     */
    public function getTimezone() {
        return $this->timezone;
    }
}