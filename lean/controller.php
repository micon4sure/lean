<?php

namespace lean;

class Controller {

    /**
     * @var Application
     */
    private $application;

    /**
     * @param Application $application
     */
    public function __construct(Application $application) {
        $this->application = $application;
        $this->init();
    }

    /**
     * Called upon construction
     */
    protected function init() {
    }

    /**
     * @return Application
     */
    protected function getApplication() {
        return $this->application;
    }
}