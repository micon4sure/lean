<?php
namespace lean;

const ROOT_PATH = __DIR__;

/**
 * lean autoloader.
 * Loads the library item depending on its name.
 * The name will be split by backslashes first: lean\form\element\Text => [lean, form, element, Text]
 * The last chunk of the splitted name will be split by underscores and only the first chunk is taken into consideration:
 * \lean\Exception_Template_TemplatePathNotFound => [lean, exception]
 */
class Autoload {

    /**
     * @var array
     */
    protected $libraries = array();

    protected $initSlim = false;

    protected $initLean = false;

    /**
     *
     */
    public function __construct() {
        spl_autoload_register(array($this, 'load'));
    }

    /**
     * Register a library to be automatically loaded
     *
     * @param string $namespace
     * @param string $directory
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function register($namespace, $directory) {
        // check for directory validity
        $realDirectory = realpath($directory);
        if(!strlen($directory) || !strlen($realDirectory))
            throw new \InvalidArgumentException("Directory '$directory' is invalid.");
        $directory = $realDirectory;

        if (!file_exists($directory)) {
            throw new \Exception("Directory '$directory' does not exist");
        }
        $this->libraries[$namespace] = $directory;
    }

    /**
     * Actual autoload function that is being registered with the SPL
     *
     * @param string $name
     */
    public function load($name) {
        foreach ($this->libraries as $namespace => $directory) {
            if (substr($name, 0, strlen($namespace)) != $namespace) {
                continue;
            }
            $subName = substr($name, strlen($namespace) + 1); // +1 for leading backspace
            $tree = explode('\\', $subName);
            $item = array_pop($tree);
            foreach ($tree as $subdir) {
                $directory .= '/' . strtolower($subdir);
            }

            $itemParts = explode('_', $item);
            $path = sprintf('%s/%s.php', $directory, strtolower($itemParts[0]));

            if (file_exists($path)) {
                require_once($path);
            }
        }
    }

    /**
     * Add the lean library to autoloading
     */
    public function loadLean() {
        $this->register('lean', ROOT_PATH . '/lib');
    }

    /**
     * Initialize Slim
     */
    public function loadSlim() {
        include __DIR__ . '/../external/slim/Slim/Slim.php';
    }
}