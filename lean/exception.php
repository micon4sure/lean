<?php

namespace lean;

/**
 * lean exception
 */
class Exception extends \Exception {
    function __invoke() {
        echo "OH HAI THER!";
    }
}