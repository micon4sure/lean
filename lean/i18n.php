<?php
namespace lean;

/**
 * Internationalization class
 * FIXME additional arguments for callback
 */

class I18N {

    /**
     * @var string the root directory of the translations
     */
    private $dir;

    /**
     * @var string the locale to be used
     */
    private $locale;

    /**
     * @var array the translations as key => value pairs
     */
    private $translations;

    /**
     * @var I18N instance for static access
     */
    private static $instance;

    /**
     * @var callable callback for when a key could not be resolved
     */
    private $callback;

    /**
     * @param $dir    string
     * @param $locale string
     */
    public function __construct($dir, $locale) {
        $this->dir = $dir;
        $this->locale = $locale;

        self::$instance = $this;
    }

    /**
     * @return I18N
     * @throws Exception
     */
    public static function instance(I18N $instance = null) {
        if (func_num_args() == 1) {
            self::$instance = $instance;
            return;
        }
        if (!self::$instance) {
            throw new Exception('I18N not initialized. Create a new instance to make it happen.');
        }
        return self::$instance;
    }

    /**
     * Resolve an i18n string by it's key
     * If additional parameters to the key are passed, they will be sprintf'd
     *
     * Example:
     * Your i18n string is 'foo' => 'bar %s kos'
     * You call resolve('foo', 'qux')
     * You will get 'bar qux kos'
     *
     * @param $key string
     *
     * @return string
     */
    public function resolve($key) {
        // shift key from arguments
        $args = func_get_args();
        $key = array_shift($args);

        // look up the key
        $lookup = $this->lookup($key);
        if ($lookup === null) {
            // call the callback if the key could not be resolved
            if ($this->callback === null) {
                return $key;
            }
            return call_user_func($this->callback, $key, $this);
        }

        // vsprtintf the i18n string if there are additional arguments
        return count($args)
            ? vsprintf($lookup, $args)
            : $lookup;
    }

    /**
     * Shortcut resolve method
     *
     * @static
     *
     * @param $key string
     *
     * @return mixed
     * @throws Exception
     */
    public static function translate($key) {
        if (self::$instance === null) {
            throw new Exception('no instance of ' . get_called_class() . ' initialized');
        }
        return call_user_func_array(array(self::$instance, 'resolve'), func_get_args());
    }

    /**
     * @param $locale string
     *
     * @return string|I18N
     */
    public function locale($locale = null) {
        if (func_num_args() == 0) {
            return $this->locale;
        }
        $this->locale = $locale;
        return $this;
    }

    /**
     * Include the language file if not done yet.
     * Return the value to the key
     *
     * @param $key
     *
     * @return mixed
     * @throws Exception
     */
    public function lookup($key) {
        if ($this->translations === null) {
            $file = sprintf('%s/%s.php', $this->dir, $this->locale);
            if (!file_exists($file)) {
                throw new Exception("translation file not found: $file");
            }
            $this->translations = include $file;
        }

        // return null if the translation key is not present
        if (!isset($this->translations[$key])) {
            return null;
        }
        return $this->translations[$key];
    }

    /**
     * Set the callable that will be used when a key could not be resolved
     * callable should expect two parameters: translation key and i18n instance
     *
     * @param $callable
     */
    public function callback($callable) {
        $this->callback = $callable;
    }
}