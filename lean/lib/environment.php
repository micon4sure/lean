<?php
namespace lean;

class Environment {

    /**
     * @var array
     */
    private $settings;

    /**
     * Parse $file ini-style, split the names of the headers at ":" (name : parent)
     * Load the $environment
     *
     * @param string $file
     * @param string $environment
     * @throws Exception
     */
    public function __construct($file, $environment = null) {
        // parse file
        $raw = $this->loadIni($file);
        $parsed = $this->parseSettings($raw);

        if (!array_key_exists($environment, $parsed)) {
            throw new \lean\Exception("Unknown environment '$environment'");
        }

        // merge settings with possible parents
        $current = $parsed[$environment];
        $merged = $current['settings'];
        while ($current['parent'] !== null) {
            if (!array_key_exists($current['parent'], $parsed)) {
                throw new \lean\Exception("Invalid parent environment '{$current['parent']}' for environment '{$current['name']}'");
            }

            $parent = $parsed[$current['parent']];
            $merged = array_merge($parent['settings'], $merged);
            $current = $parent;
        }
        $this->settings = $merged;
    }

    /**
     * Load an ini style configuration file. Throw Exception if file could not be read
     * before parsing or parsing went wrong
     *
     * @param $file
     * @return array
     */
    protected function loadIni($file) {
        if(!is_readable($file))
            throw new Exception("File '$file' could not be read.");
        $parsed = parse_ini_file($file, true);
        if ($parsed === false)
            throw new Exception("Could not parse file '$file' in ini style.'");
        return $parsed;
    }

    /**
     * Parse ini headers to environment names + parent names
     *
     * @param array $raw
     * @return array
     */
    protected function parseSettings(array $raw) {
        $parsed = array();
        // split sections into headers and settings
        foreach ($raw as $name => $settings) {
            if (strpos($name, ':') === false) {
                $name = trim($name);
                $parsed[$name] = array('settings' => $settings, 'parent' => null);
                continue;
            }
            list($name, $parent) = explode(':', $name);
            $name = trim($name);
            $parent = trim($parent);
            $parsed[$name] = array('name' => $name, 'settings' => $settings, 'parent' => $parent);
        }
        return $parsed;
    }

    /**
     * @param array $settings
     */
    public function setDefaultSettings(array $settings) {
        foreach ($settings as $key => $value) {
            if (!array_key_exists($key, $this->settings)) {
                $this->settings[$key] = $value;
            }
        }
    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function get($key) {
        if (!array_key_exists($key, $this->settings)) {
            throw new \lean\Exception("Environment setting '$key' not found'");
        }
        return $this->settings[$key];
    }
}

class Environment_Local extends Environment {
    /**
     * Set local as the environment
     *
     * @param string $file
     * @param null $environment
     */
    public function __construct($file, $environment = null) {
        parent::__construct($file, 'local');
    }

    /**
     * Add environment local to parsed environments
     *
     * @param array $raw
     * @return array
     */
    protected function parseSettings(array $raw) {
        $settings = parent::parseSettings($raw);
        // parse file
        $rawLocal = $this->loadIni(APPLICATION_ROOT . '/config/local.ini');
        $local = parent::parseSettings($rawLocal);
        return array_merge($local, $settings);
    }
}