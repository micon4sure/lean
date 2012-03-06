<?php

namespace lean;

/**
 * Highly configurable dumping class
 * Also works from CLI
 */
class Dump {
    // constants
    /**
     * Spacing to be used to indent lines
     */
    const SPACING = '    ';
    /**
     * All the magic methods a class can have
     */
    const MAGIC_METHODS = '__construct,__destruct,__call,__callStatic,__get,__set,__isset,__unset,__sleep,__wakeup,__toString,__invoke,__set_state,__clone';
    //--------------------------------------------------------------------------

    // properties
    /**
     * Recursion level
     * @var int */
    private $levels = 1;
    /**
     * Should methods be dumped too?
     * @var boolean */
    private $methods = true;
    /**
     * Should methods / properties be sorted by visibility and alphabetically?
     * @var boolean */
    private $sort = true;
    /**
     * Should parent properties be dumped too?
     * @var boolean */
    private $parent = true;
    /**
     * Should magic methods be shown if declared?
     * @var boolean */
    private $magic = false;
    /**
     * Should dumps be wrapped in HTML? Important for CLI
     * @var boolean */
    private $wrap;
    /**
     * Saves the caller of the dump so it can be shown
     * @var string */
    private $caller;
    /**
     * The prototype to be used when create()ing a new dump
     * @var lean\Dump */
    private static $prototype;
    //----------------------------------------------------------------------------

    // creation and options
    /**
     * Set a prototype that will be used when creating instances
     * @param self $prototype
     */
    public static function setPrototype(self $prototype) {
        self::$prototype = $prototype;
    }

    /**
     * Sets wrap if PHP_API is not cli
     */
    private function __construct() {
        $this->wrap(PHP_SAPI != 'cli');
    }

    /**
     * Create a new dump (from prototype if set)
     * @return lean\Dump
     * @param int $levels
     */
    public static function create($levels = 1) {
        $instance = self::$prototype
            ? clone self::$prototype
            : new self;
        $instance->levels($levels);
        return $instance;
    }

    /**
     * @param boolean $bool should output be wrapped into an html element?
     * @return lean\Dump
     */
    public function wrap($bool = false) {
        $this->wrap = $bool;
        return $this;
    }

    /**
     * @param boolean $bool show or hide methods
     * @return lean\Dump
     */
    public function methods($bool = false) {
        $this->methods = $bool;
        return $this;
    }

    /**
     * @param boolean $bool sort methods and properties?
     * @return lean\Dump
     */
    public function sort($bool = false) {
        $this->sort = $bool;
        return $this;
    }

    /**
     * @param boolean $bool show magic functions?
     * @return lean\Dump
     */
    public function magic($bool = true) {
        $this->magic = $bool;
        return $this;
    }

    /**
     * @param boolean $bool show parent properties?
     * @return lean\Dump
     */
    public function parentProperties($bool = false) {
        $this->parent = $bool;
        return $this;
    }

    /**
     * Set the caller, needed to know where the dump came from in case a shortcut method was used
     * @param array $caller_
     */
    public function caller(array $caller) {
        $this->caller = $caller;
        return $this;
    }

    /**
     * Shortcut method for a flat dump
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
     * @param $levels
     */
    public static function deep($levels)
    {
        $trace = debug_backtrace();
        $args = func_get_args();
        $levels = array_shift($args);
        self::create($levels)->caller(reset($trace))->goes($args);
    }

    /**
     * Set the depth of the dump
     * @return lean\Dump
     */
    public function levels($levels) {
        $this->levels = $levels;
        return $this;
    }

    /**
     * Dump all method arguments
     */
    public function goes() {
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
                if (preg_match(sprintf('#^%1$s.+%1$s(.+?)$#', preg_quote(chr(0))), $k, $match)) {
                    $k = $match[1];
                }
                $values[$k] = $v;
            }
            if ($this->parent) {
                while (($object === null && $object = new \ReflectionObject($arg)) || ($object && $object = $object->getParentClass())) {
                    foreach ($object->getProperties() as $property) {
                        if (!array_key_exists($property->getName(), $values)) {
                            continue;
                        }
                        else {
                            $properties[$this->getVisibility($property) . ' ' . $property->getName()] = $values[$property->getName()];
                        }
                    }
                }
            }
            else {
                $object = new \ReflectionObject($arg);
                foreach ($object->getProperties() as $property) {
                    if (!array_key_exists($property->getName(), $values)) {
                        continue;
                    }
                    else {
                        $properties[$this->getVisibility($property) . ' ' . $property->getName()] = $values[$property->getName()];
                    }
                }
            }
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
            if ($this->magic) {
                $magic = array();
                foreach (explode(',', self::MAGIC_METHODS) as $method) {
                    if (method_exists($arg, $method)) {
                        if ($method == '__toString') {
                            $magic[] = str_repeat(self::SPACING, $levels) . '__toString: ' . (string)$arg . "\n";
                        }
                        else {
                            $magic[] = str_repeat(self::SPACING, $levels) . $method . "\n";
                        }
                    }
                }
                if ($magic) {
                    echo "\n" . str_repeat(self::SPACING, $levels) . "---! ::: MAGIC METHODS ::: !---\n";
                    echo implode('', $magic);
                }
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