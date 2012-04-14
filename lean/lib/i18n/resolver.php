<?php
namespace lean\i18n;

/**
 * Internationalization class
 * Uses strings from a flat array until ICU comes of age
 */
class Resolver {

    /**
     * @var string the root directory of the translations
     */
    private $dir;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var array
     */
    private $localizations;

    /**
     * @param string $dir
     * @param \lean\i18n\Context $context
     */
    public function __construct($dir, Context $context) {
        $this->dir = $dir;
        $this->locale = $context->getLocale();
    }

    /**
     * Resolve a localization string by its key
     * If additional parameters to the key are passed, they will be sprintf'd
     *
     * @param string $key
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
            return $key;
        }

        return $lookup;
    }

    /**
     * Return the localization string to the key.
     * Throw exception if localization file not found or key not present.
     *
     * @param string $key
     *
     * @return mixed
     * @throws Exception
     */
    protected function lookup($key) {
        // include language file if not present
        if ($this->localizations === null) {
            $file = "{$this->dir}/{$this->locale}.php";
            if (!file_exists($file)) {
                throw new \lean\Exception("Localization file not found: $file");
            }
            $this->localizations = include $file;
        }

        // return null if the translation key is not present
        if (!isset($this->localizations[$key])) {
            throw new \lean\Exception("Localization string not found for key '$key' and locale '{$this->locale}'");
        }
        return $this->localizations[$key];
    }
}