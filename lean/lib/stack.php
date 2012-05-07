<?php
namespace lean;

/**
 * Classic stack. Just with pull instead of push (you are free to override this)
 */
class Stack extends Stack_Private {
    private $items = array();

    /**
     * Push something onto the stack
     *
     * @param string $key
     * @param mixed $item
     */
    public function push($key, $item) {
        $this->items[$key] = $item;
    }

    /**
     * Get the topmost element of the stack
     *
     * @return mixed
     */
    public function current() {
        return parent::current();
    }

    /**
     * Pull the topmost element off the stack
     *
     * @return mixed
     */
    public function pull() {
        return array_pop($this->items);
    }
}

/**
 * Private access to current() - version of the stack
 */
class Stack_Private {
    /**
     * Get the topmost element of the stack
     *
     * @return mixed
     */
    protected function current() {
        return end($this->items);
    }
}