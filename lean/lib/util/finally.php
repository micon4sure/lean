<?php
namespace lean\util;
/*
 * Copyright (C) 2012 Michael Saller
 * Licensed under MIT License, which is found under /path/to/stack/LICENSE
 */

/**
 * Finally statement hack (*bleh*)
 */
class Finally {
    /**
     * @param callable $code
     * @param callable $finally
     */
    public function __construct($code, $finally) {
        try {
            $code();
        } catch(\Exception $e) {
            $finally();
            throw $e;
        }
        $finally();
    }
}