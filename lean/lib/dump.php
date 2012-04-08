<?php

namespace lean;

/**
 * Highly configurable dumping class
 * Also works from CLI
 * TODO static properties
 */ class Dump {

    // constants
    /**
     * Spacing to be used to indent lines
     */
    const SPACING = '    ';

    const VISBILITY_PRIVATE = 0;

    const VISBILITY_PROTECTED = 1;

    const VISBILITY_PUBLIC = 2;

    /**
     * All the magic methods a class can have
     */
    const MAGIC_METHODS = '__construct,__destruct,__call,__callStatic,__get,__set,__isset,__unset,__sleep,__wakeup,__toString,__invoke,__set_state,__clone';

    //--------------------------------------------------------------------------

    // properties
    /**
     * Recursion level
     *
     * @var int */
    private $levels = 1;

    /**
     * Should methods be dumped too?
     *
     * @var boolean */
    private $methods = true;

    /**
     * Should methods / properties be sorted by visibility and alphabetically?
     *
     * @var boolean */
    private $sort = true;

    /**
     * Should the string representation of a class be shown if __toString is present?
     *
     * @var boolean */
    private $showString = true;

    /**
     * Should dumps be wrapped in HTML? Important for CLI
     *
     * @var boolean */
    private $wrap;

    /**
     * Saves the caller of the dump so it can be shown
     *
     * @var string */
    private $caller;

    /**
     * Flush OB on goes?
     *
     * @var bool
     */
    private $flush = true;

    /**
     * The prototype to be used when create()ing a new dump
     *
     * @var Dump */
    private static $prototype;

    //----------------------------------------------------------------------------

    // creation and options
    /**
     * Set a prototype that will be used when creating instances
     *
     * @param self $prototype
     */
    public static function prototype(self $prototype = null) {
        if (self::$prototype === null) {
            self::$prototype = new self;
        }

        if (func_num_args() == 0) {
            return self::$prototype;
        }

        self::$prototype = $prototype;
        return $prototype;
    }

    /**
     * Sets wrap if PHP_API is not cli
     */
    private function __construct() {
        $this->wrap(PHP_SAPI != 'cli');
    }

    /**
     * Create a new dump (from prototype if set)
     *
     * @return Dump
     *
     * @param int $levels
     */
    public static function create($levels = 1) {
        $instance = clone self::prototype();
        $instance->levels($levels);
        return $instance;
    }

    /**
     * @param boolean $bool should output be wrapped into an html element?
     *
     * @return Dump
     */
    public function wrap($bool = false) {
        $this->wrap = $bool;
        return $this;
    }

    /**
     * @param boolean $bool show or hide methods
     *
     * @return Dump
     */
    public function methods($bool = false) {
        $this->methods = $bool;
        return $this;
    }

    /**
     * @param boolean $bool sort methods and properties?
     *
     * @return Dump
     */
    public function sort($bool = false) {
        $this->sort = $bool;
        return $this;
    }

    /**
     * @param boolean $bool should the string representation of a class be shown if __toString is present?
     *
     * @return Dump
     */
    public function showString($bool = false) {
        $this->showString = $bool;
        return $this;
    }

    /**
     * Flush OB on goes?
     *
     * @param bool $bool
     */
    public function flush($bool = false) {
        $this->flush = $bool;
        return $this;
    }

    /**
     * Set the caller, needed to know where the dump came from in case a shortcut method was used
     *
     * @param array $caller_
     */
    public function caller(array $caller) {
        $this->caller = $caller;
        return $this;
    }

    /**
     * Shortcut method for a flat dump
     *
     * @return boolean
     */
    public static function flat() {
        $trace = debug_backtrace();
        foreach (func_get_args() as $arg) {
            self::create()->caller(reset($trace))->goes($arg);
        }
        return true;
    }

    /**
     * Shortcut method for a deep dump
     *
     * @param $levels
     */
    public static function deep($levels) {
        $trace = debug_backtrace();
        $args = func_get_args();
        $levels = array_shift($args);
        $instance = self::create($levels)->caller(reset($trace));
        call_user_func_array(array($instance, 'goes'), $args);
    }

    /**
     * Set the depth of the dump
     *
     * @param int $levels
     *
     * @return Dump
     */
    public function levels($levels) {
        $this->levels = $levels;
        return $this;
    }

    /**
     * Dump all method arguments
     *
     * @return Dump
     */
    public function goes() {
        if ($this->flush) {
            $ob = array();
            $level = ob_get_level();
            for ($i = 0; $i < $level; $i++) {
                $ob[] = ob_get_clean();
            }
        }
        if (!$this->caller) {
            $trace = debug_backtrace();
            $this->caller(reset($trace));
        }
        $args = func_get_args();
        foreach ($args as $arg) {
            if ($this->wrap) {
                echo '<pre style="clear:both; text-align:left;background-color:gray;border:1px solid black;margin-top:5px;color:white;font-family:monospace;padding:5px;font-size:14px;z-index:1000000000;position:relative">';
            }
            $this->printRecursively($arg);
            if ($this->wrap) {
                printf("\n<span style=\"font-size:12px;\">%s:%s</span></pre>", $this->caller['file'], $this->caller['line']);
            }
            else {
                printf("\n%s:%s\n", $this->caller['file'], $this->caller['line']);
            }
        }
        if ($this->flush) {
            foreach ($ob as $buffer) {
                ob_start();
                echo $buffer;
            }
        }
        return $this;
    }

    //--------------------------------------------------------------------------

    /**
     * Actual dumping method
     */
    private function printRecursively($arg, $levels = 1) {
        if ($arg === true) {
            echo 'true(bool)';
        }
        elseif ($arg === false) {
            echo 'false(bool)';
        }
        elseif ($arg === null) {
            echo 'NULL';
        }
        elseif (is_string($arg)) {
            printf("%s(string:%d)\n", $arg, mb_strlen($arg));
        }
        elseif (is_scalar($arg)) {
            printf('%s(%s)', $arg, gettype($arg));
        }
        elseif (is_object($arg)) {
            printf("Object(%s)\n%s{\n", get_class($arg), str_repeat(self::SPACING, $levels - 1));
            // prepare properties
            $properties = array();
            $object = null;
            $values = array();
            foreach ((array)$arg as $k => $v) {
                $values[$k] = $v;
            }

            // gather object values
            $object = new \ReflectionObject($arg);
            do {
                foreach ($object->getProperties() as $property) {
                    if ($property->isPublic()) {
                        $visibility = self::VISBILITY_PUBLIC;
                    }
                    else if ($property->isProtected()) {
                        $visibility = self::VISBILITY_PROTECTED;
                    }
                    else if ($property->isPrivate()) {
                        $visibility = self::VISBILITY_PRIVATE;
                    }
                    $property->setAccessible(true);
                    $properties[$this->getVisibility($property) . ' ' . $property->getName()] = $property->getValue($arg);
                    if ($visibility == self::VISBILITY_PROTECTED || $visibility == self::VISBILITY_PROTECTED) {
                        $property->setAccessible(false);
                    }
                }
            } while ($object = $object->getParentClass()); // loop through class parents if there are any


            if ($this->sort) {
                uksort($properties, array($this, 'sortCallback'));
            }
            echo str_repeat(self::SPACING, $levels) . "---! ::: PROPERTIES ::: !---\n";
            foreach ($properties as $k => $v) {
                if (is_object($v)) {
                    if ($levels < $this->levels) {
                        printf(str_repeat(self::SPACING, $levels) . '%s: ', $k);
                        $this->printRecursively($v, $levels + 1);
                        continue;
                    }
                    if (in_array('__toString', get_class_methods($v))) {
                        printf(str_repeat(self::SPACING, $levels) . "%s: '%s' Object(%s)\n", $k, (string)$v, get_class($v));
                    }
                    else {
                        printf(str_repeat(self::SPACING, $levels) . "%s: Object(%s)\n", $k, get_class($v));
                    }
                }
                elseif (is_array($v)) {
                    if ($levels < $this->levels) {
                        echo str_repeat(self::SPACING, $levels) . $k . ': ';
                        $this->printRecursively($v, $levels + 1);
                    }
                    else {
                        printf(str_repeat(self::SPACING, $levels) . "%s: Array(%d)\n", $k, count($v));
                    }
                }
                elseif (is_string($v)) {
                    printf(str_repeat(self::SPACING, $levels) . "%s: '%s'(string:%d)\n", $k, $v, mb_strlen($v));
                }
                elseif (is_null($v)) {
                    printf(str_repeat(self::SPACING, $levels) . "%s: NULL\n", $k);
                }
                elseif ($v === true) {
                    printf(str_repeat(self::SPACING, $levels) . "%s: true(bool)\n", $k);
                }
                elseif ($v === false) {
                    printf(str_repeat(self::SPACING, $levels) . "%s: false(bool)\n", $k);
                }
                else {
                    printf(str_repeat(self::SPACING, $levels) . "%s: %s(%s)\n", $k, $v, gettype($v));
                }
            }
            if ($this->methods) {
                echo "\n" . str_repeat(self::SPACING, $levels) . "---! ::: METHODS ::: !---\n";
                $methods = array();
                $object = new \ReflectionObject($arg);
                foreach ($object->getMethods() as $method) {
                    $methods[] = $this->getVisibility($method) . ' ' . $method->getName();
                }
                if ($this->sort) {
                    usort($methods, array($this, 'sortCallback'));
                }
                foreach ($methods as $method) {
                    echo str_repeat(self::SPACING, $levels) . "$method\n";
                }
            }
            if ($this->showString && method_exists($arg, '__toString')) {
                $string = (string)$arg;
                echo str_repeat(self::SPACING, $levels) . "this object to string: '$string'(string:" . strlen($string) . ")\n";
            }
            echo str_repeat(self::SPACING, $levels - 1) . "}\n";
        }
        elseif (is_array($arg)) {
            printf("Array(%s)\n%s[\n", count($arg), str_repeat(self::SPACING, $levels - 1));

            foreach ($arg as $k => $v) {
                //issue seems to be resolved in this version
                //if(preg_match(sprintf('#^%1$s.+%1$s(.+?)$#', preg_quote(chr(0))), $k, $match))
                //{
                //  $k=$match[1];
                //}
                if (is_object($v)) {
                    if ($levels < $this->levels) {
                        printf(str_repeat(self::SPACING, $levels) . '%s: ', $k);
                        $this->printRecursively($v, $levels + 1);
                        continue;
                    }
                    if (in_array('__toString', get_class_methods($v))) {
                        printf(str_repeat(self::SPACING, $levels) . "%s: '%s' Object(%s)\n", $k, (string)$v, get_class($v));
                    }
                    else {
                        printf(str_repeat(self::SPACING, $levels) . "%s: Object(%s)\n", $k, get_class($v));
                    }
                }
                elseif (is_array($v)) {
                    if ($levels < $this->levels) {
                        echo str_repeat(self::SPACING, $levels) . $k . ': ';
                        $this->printRecursively($v, $levels + 1);
                    }
                    else {
                        printf(str_repeat(self::SPACING, $levels) . "%s: Array(%d)\n", $k, count($v));
                    }
                }
                elseif (is_string($v)) {
                    printf(str_repeat(self::SPACING, $levels) . "%s: '%s'(string:%d)\n", $k, $v, mb_strlen($v));
                }
                elseif (is_null($v)) {
                    printf(str_repeat(self::SPACING, $levels) . "%s: NULL\n", $k);
                }
                elseif ($v === true) {
                    printf(str_repeat(self::SPACING, $levels) . "%s: true(bool)\n", $k);
                }
                elseif ($v === false) {
                    printf(str_repeat(self::SPACING, $levels) . "%s: false(bool)\n", $k);
                }
                else {
                    printf(str_repeat(self::SPACING, $levels) . "%s: %s(%s)\n", $k, $v, gettype($v));
                }
            }
            echo str_repeat(self::SPACING, $levels - 1) . "]\n";
        }
        return $this;
    }

    /**
     * @return string a string representation of the visibility of a method or property
     */
    private function getVisibility($reflection) {
        if ($reflection->isPublic()) {
            return '+';
        }
        elseif ($reflection->isProtected()) {
            return '#';
        }
        elseif ($reflection->isPrivate()) {
            return '-';
        }
    }

    /**
     * Sorts methods and properties by their visibility and alphabetically
     */
    private function sortCallback($a, $b) {
        $visibilityA = substr($a, 0, 1);
        $visibilityB = substr($b, 0, 1);

        if ($visibilityA == $visibilityB) {
            return substr($a, 1) > substr($b, 1)
                ? 1
                : -1;
        }
        elseif ($visibilityA == '+' && ($visibilityB == '#' || $visibilityB == '-')) {
            return -1;
        }
        elseif ($visibilityA == '#') {
            return $visibilityB == '-'
                ? -1
                : 1;
        }
        elseif ($visibilityA == '-') {
            return 1;
        }
    }

}