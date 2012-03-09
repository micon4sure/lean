<?php

namespace lean\application;

class Controller {

    private $application;

    public function __construct(\lean\Application $application) {
        $this->application = $application;
    }

    /**
     * @return Application
     */
    protected function application() {
        return $this->application;
    }
}