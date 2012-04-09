<?php
namespace lean;
class Migration {
    private $history;

    public function __construct($historyFile) {
        if(!file_exists($historyFile)) {
            if(!is_writable(dirname($historyFile))) {
                throw new EXception("history file '$historyFile' does not exist and can't write to " . dirname($historyFile));
            }
            file_put_contents($historyFile, '{}');
        }
        $this->history = json_decode(file_get_contents($historyFile));
    }
}