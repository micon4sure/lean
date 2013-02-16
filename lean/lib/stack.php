<?php
namespace lean;

/**
 * Classic stack. Just with pull instead of push (you are free to override this)
 */
class Stack {
    protected $items = array();

    /**
     * Push something onto the stack
     *
     * @param mixed $item
     */
    public function push($item) {
        $this->items[] = $item;
    }

    /**
     * @return int
     */
    public function count() {
        return count($this->items);
    }

    /**
     * Get the topmost element of the stack
     *
     * @return mixed
     */
    public function current() {
        return end($this->items);
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