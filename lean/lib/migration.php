<?php
namespace lean;

/**
 * Self explanatory?
 */
interface Migration {

    public function up();

    public function down();
}


/**
 * Migration manager executes migrations and keeps track of the migration level.
 */
class Migration_Manager {

    const HISTORY_FILENAME = 'history.dat';
    const HISTORY_SEPERATOR = ':::';

    /**
     * @var array
     */
    private $history;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var array available migrations, ordered by their level, ascending
     */
    private $available = array();

    /**
     * @var bool directory already initialized?
     */
    private $init = false;

    /**
     * @var array save initialized directories so as to not initialize one twice
     */
    private static $directories = array();

    /**
     * @param string $directory the directory the migrations reside in
     */
    public function __construct($directory) {
        $this->directory = $directory;
        if (in_array($directory, self::$directories)) {
            throw new Exception('This directory has alread been initialized!');
        }

        self::$directories[] = $directory;
    }

    /**
     * Read the migration directory.
     * Get available migrations and parse the history file.
     *
     * @throws Exception
     */
    public function init() {
        // do not initialize the directory twice
        if ($this->init) {
            return;
        }
        $this->init = true;
        $history = $this->directory . '/' . self::HISTORY_FILENAME;

        // check for history file existence
        if (!file_exists($history)) {
            if (!is_writable(dirname($history))) {
                throw new Exception("History file '$history' does not exist and can't write to directory " . dirname($history));
            }
            // history file not found, write it
            touch($history);
        }

        // read history file, avoid empty entries in history array
        $contents = file_get_contents($history);
        $this->history = $contents
            ? explode(self::HISTORY_SEPERATOR, $contents)
            : array();

        // read available migrations
        foreach (new \DirectoryIterator($this->directory) as $migrationFile) {
            if (!preg_match('#^(\d+).+\.m\.php$#', $migrationFile->getFilename(), $match)) {
                continue;
            }

            // check for validity and add to available migrations
            $migration = include $migrationFile->getPathname();
            if (!$migration instanceof Migration) {
                throw new Exception("Migration file '$migrationFile' matched the filename pattern but return result is not a valid migration");
            }
            $this->available[$match[1]] = $migration;

            // need to sort by keys to get them in the right order for execution
            ksort($this->available);
        }
    }

    /**
     * @param null $levels
     * @return array
     */
    public function getPending($levels = null) {
        $this->init();
        return array_keys(array_slice($this->available, count($this->history), $levels));
    }

    /**
     * Upgrade the application by n steps.
     * If step is null, the application will be upgraded the most recent level
     *
     * @param int  $levels
     * @return array
     */
    public function upgrade($levels = null) {
        $this->init();

        // run the migrations and save execution in history
        $upgrade = array_slice($this->available, count($this->history), $levels);
        $done = array();
        foreach ($upgrade as $level => $migration) {
            $migration->up();
            $this->history[] = $done[] = $level;
            $this->writeHistory();
        }
        return $done;
    }

    /**
     * Downgrade the application by n steps.
     * If step is null, the application will be downgraded by one level.
     *
     * @param int  $steps
     * @return array
     */
    public function downgrade($steps = null) {
        $this->init();
        if ($steps === null) {
            $steps = 1;
        }

        $done = array();
        for ($i = 0; $i < $steps; $i++) {
            // remove level from history, get the migration and run it.
            $level = $done[] = array_pop($this->history);
            $migration = $this->available[$level];
            $migration->down();
            $this->writeHistory();
        }

        return $done;
    }

    /**
     * Get the history as an array of executed levels
     *
     * @return array
     */
    public function getHistory() {
        return $this->history;
    }

    /**
     * Write the migration history to file
     */
    private function writeHistory() {
        $file = $this->directory . '/' . self::HISTORY_FILENAME;
        file_put_contents($file, implode(self::HISTORY_SEPERATOR, $this->history));
    }
}