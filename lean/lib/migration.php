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

        // check for history file existence and writability
        if (!file_exists($history)) {
            if (!is_writable(dirname($history))) {
                throw new Exception("History file '$history' does not exist and can't write to directory " . dirname($history));
            }
            // history file not found, write it
            touch($history);
        }
        if(!is_writable($history)) {
            throw new Exception("History file '$history' is not writable");
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
     * Get available migration levels
     * @return array
     */
    public function getAvailable() {
        $this->init();
        return array_keys($this->available);
    }

    /**
     * Get pending migration levels
     * @param null $levels
     * @return array
     */
    public function getPending($levels = null) {
        $this->init();
        return array_keys(array_slice($this->available, count($this->history), $levels));
    }

    /**
     * Migrate to a specific level
     * @param $level
     */
    public function migrateTo($level) {
        $this->init();

        if(!array_key_exists("$level", $this->available))
            throw new Exception("Migration level '$level' is not available!");

        $done = array();


        if(in_array("$level", $this->history)) {
            // downgrade until at desired level
            while(count($this->history) && end($this->history) != "$level") {
                $done = array_merge($done, $this->downgrade());
            }
        } else {
            // upgrade until at desired level
            while(!in_array("$level", $this->history)) {
                $done = array_merge($done, $this->upgrade());
            }
        }
        return $done;
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

        // downgrade as far as $steps or level 0
        $done = array();
        for ($i = 0; $i < $steps; $i++) {
            if(!count($this->history))
                return $done;
            // remove level from history, get the migration and run it.
            $done[] = $level = array_pop($this->history);
            $migration = $this->available[$level];
            $migration->down();
            $this->writeHistory();
        }

        return $done;
    }

    /**
     * Reset to zero
     * @return array
     */
    public function reset() {
        $this->init();
        $done = array();
        while(count($this->history)) {
            $done = array_merge($done, $this->downgrade());
        }
        return $done;
    }

    /**
     * Get the history as an array of executed levels
     *
     * @return array
     */
    public function getHistory() {
        $this->init();
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