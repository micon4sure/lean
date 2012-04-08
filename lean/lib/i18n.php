<?php
namespace lean;

/**
 * Internationalization class
 */

class I18N {

    /**
     * @var string the root directory of the translations
     */
    private $dir;

    /**
     * @var string[]
     */
    private $locales = array();

    /**
     * @var array of arrays, [locale => {key: value, key2: value2}]
     */
    private $translations = array();

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
        $this->locales[] = $locale;
    }

    /**
     * Push a locale onto the locale stack
     *
     * @param string $locale
     * @return \lean\I18N_Resolve
     */
    public function pushLocale($locale) {
        $this->locales[] = $locale;
        return $this;
    }

    /**
     * Pop a locale from the locale stack
     *
     * @return mixed
     */
    public function popLocale() {
        return array_pop($this->locales);
    }


    /**
     * Resolve an i18n string by its key
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
     * Include the language file if not done yet.
     * Return the value to the key
     *
     * @param string $key
     *
     * @return mixed
     * @throws Exception
     */
    protected function lookup($key) {
        $locale = end($this->locales);
        if (!array_key_exists($locale, $this->translations)) {
            $file = sprintf('%s/%s.php', $this->dir, $locale);
            if (!file_exists($file)) {
                throw new Exception("translation file not found: $file");
            }
            $this->translations[$locale] = include $file;
        }

        // return null if the translation key is not present
        if (!isset($this->translations[$locale][$key])) {
            return null;
        }
        return $this->translations[$locale][$key];
    }

    /**
     * Set the callable that will be used when a key could not be resolved
     * callable should expect two parameters: translation key and i18n instance
     *
     * @param $callable
     */
    public function setUnresolvedCallback($callable) {
        $this->callback = $callable;
    }
}