<?php
namespace lean;

class Util_DateTime extends \DateTime {
    public function getYear() {
        return $this->format('Y');
    }
    public function getMonth() {
        return $this->format('m');
    }
    public function getDay() {
        return $this->format('d');
    }

    public function getHour() {
        return $this->format('H');
    }
    public function getMinute() {
        return $this->format('i');
    }
    public function getSecond() {
        return $this->format('s');
    }
}
