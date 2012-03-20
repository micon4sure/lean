<?php

namespace lean;

class Controller {

    /**
     * @var Application
     */
    private $application;

    /**
     * @var Util_ArrayObject
     */
    private $params;

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

    public function setParams(Util_ArrayObject $params) {
        $this->params = $params;
        return $this;
    }

    public function getParams() {
        return $this->params;
    }

    public function getParam($key) {
        return $this->params->{$key};
    }

    public function getAction() {
        return $this->getParam('action') ?: 'dispatch';
    }
}