<?php
namespace lean;

/**
 * Internationalization class
 */
class I18N
{
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
     * @param $dir string
     * @param $locale string
     */
    public function __construct($dir, $locale)
    {
        $this->dir = $dir;
        $this->locale = $locale;

        self::$instance = $this;
    }

    /**
     * @return I18N
     * @throws Exception
     */
    public function get() {
        if(!self::$instance)
            throw new Exception('I18N not initialized. Create a new instance to make it happen.');
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
     * @return string
     */
    public function resolve($key)
    {
        $args = func_get_args();
        $key = array_shift($args);
        return count($args)
            ? vsprintf($this->lookup($key), $args)
            : $this->lookup($key);
    }

    /**
     * Shortcut resolve method
     * @static
     * @param $key string
     * @return mixed
     * @throws Exception
     */
    public static function translate($key)
    {
        if (self::$instance === null)
            throw new Exception('no instance of ' . get_class($this) . ' initialized');
        return call_user_func_array(array(self::$instance, 'resolve'), func_get_args());
    }

    /**
     * @param $locale string
     * @return string|I18N
     */
    public function locale($locale = null)
    {
        if(func_num_args() == 0)
           return $this->locale;
        $this->locale = $locale;
        return $this;
    }

    /**
     * Include the language file if not done yet.
     * Return the value to the key
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function lookup($key)
    {
        if ($this->translations === null) {
            $file = sprintf('%s/%s.php', $this->dir, $this->locale);
            if (!file_exists($file))
                throw new Exception("translation file not found: $file");
            $this->translations = include $file;
        }

        return $this->translations[$key];
    }
}