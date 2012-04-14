<?php
namespace lean\i18n;

class Localization {

    private $locale;
    private $pattern;

    public function __construct($locale, $pattern) {
        $this->locale = $locale;
        $this->pattern = $pattern;
    }

    public function localize(array $values) {
        $formatter = \MessageFormatter::create($this->locale, $this->pattern);
        $result = $formatter->format($values);
        if($result === false) {
            $code = $formatter->getErrorCode();
            $message = $formatter->getErrorMessage();
            throw new \lean\Exception("Could not format message ($code) '$message'");
        }
        return $result;
    }
}