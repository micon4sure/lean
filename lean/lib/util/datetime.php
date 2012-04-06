<?php
namespace lean\util;

/**
 * Convinience layer for the built-in datetime class
 */
class DateTime extends \DateTime {
    /**
     * @return string
     */
    public function getYear() {
        return $this->format('Y');
    }
    /**
     * @return string
     */
    public function getMonth() {
        return $this->format('m');
    }
    /**
     * @return string
     */
    public function getDay() {
        return $this->format('d');
    }

    /**
     * @return string
     */
    public function getHour() {
        return $this->format('H');
    }
    /**
     * @return string
     */
    public function getMinute() {
        return $this->format('i');
    }
    /**
     * @return string
     */
    public function getSecond() {
        return $this->format('s');
    }
}
