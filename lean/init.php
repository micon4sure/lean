<?
namespace lean;

const ROOT_PATH = __DIR__;

/**
 * lean autoloader.
 * Loads the library item depending on its name.
 * The name will be split by backslashes first: lean\form\element\Text => [lean, form, element, Text]
 * The parts of the name will be
 * The last item in that array is
 */
class Autoload {

    /**
     * @var array
     */
    protected $libraries = array();

    /**
     * @param bool $loadLean
     */
    public function __construct($loadLean = true) {
        spl_autoload_register(array($this, 'load'));
        if ($loadLean) {
            $this->register('lean', __DIR__ . '/lib');
        }
    }

    /**
     * Register a library to be automatically loaded
     *
     * @param string $namespace
     * @param string $directory
     */
    public function register($namespace, $directory) {
        $directory = realpath($directory);
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
            if (substr($name, 0, strlen($namespace)) == $namespace) {
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
    }
}