<?php
namespace lean;
/**
 * lean autoloader.
 * Loads the library item depending on its name.
 * The name will be split by backslashes first: lean\form\element\Text => [lean, form, element, Text]
 * The parts of the name will be
 * The last item in that array is
 */
class Autoload {
    protected $libraries = array();

    public function __construct() {
        spl_autoload_register(array($this, 'load'));
    }


    public function register($namespace, $directory) {
        $this->libraries[$namespace] = $directory;
    }

    public function load($name) {
        foreach($this->libraries as $namespace => $directory) {
            if(substr($name, 0, strlen($namespace)) == $namespace) {
                $subName = substr($name, strlen($namespace) + 1); // +1 for leading backspace
                $tree = explode('\\', $subName);
                $item = array_pop($tree);
                foreach($tree as $subdir) {
                    $directory .= '/' . strtolower($subdir);
                }

                $itemParts = explode('_', $item);
                $path = sprintf('%s/%s.php', $directory, strtolower($itemParts[0]));
                if(file_exists($path))
                    require_once($path);
            }
        }
    }
}

$autoload = new Autoload();
// load lean library
$autoload->register('lean', __DIR__);
return $autoload;