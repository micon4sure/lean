<?php
namespace lean;

class Environment {

    /**
     * @var array
     */
    private $settings;

    public function __construct($file, $environment) {
        // parse file
        $raw = parse_ini_file($file, true);
        $parsed = array();

        // split sections into metadata and settings
        foreach ($raw as $name => $settings) {
            if (strpos($name, ':') === false) {
                $parsed[$name] = array('settings' => $settings, 'parent' => null);
                continue;
            }
            list($name, $parent) = explode(':', $name);
            $name = trim($name);
            $parent = trim($parent);
            $parsed[$name] = array('name' => $name, 'settings' => $settings, 'parent' => $parent);
        }


        if (!array_key_exists($environment, $parsed)) {
            throw new Exception("Unknown environment '$environment'");
        }

        // merge settings with possible parents
        $current = $parsed[$environment];
        $merged = $current['settings'];
        while ($current['parent'] !== null) {
            if (!array_key_exists($current['parent'], $parsed)) {
                throw new Exception("Invalid parent environment '{$current['parent']}' for environment '{$current['name']}'");
            }

            $parent = $parsed[$current['parent']];
            $merged = array_merge($parent['settings'], $merged);
            $current = $parent;
        }
        $this->settings = $merged;
    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function get($key) {
        if (!array_key_exists($key, $this->settings)) {
            throw new Exception("Environment settings '$key' not found'");
        }
        return $this->settings[$key];
    }
}