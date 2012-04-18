<?php

namespace lean\application;

class Controller {

    /**
     * @var Application
     */
    private $application;

    /**
     * @var util\Object
     */
    private $params;

    /**
     * @param Application $application
     */
    public function __construct(Application $application) {
        $this->application = $application;
    }

    /**
     * Called upon construction
     */
    public function init() {
    }

    /**
     * @param util\Object $params
     * @return Controller
     */
    public function setParams(util\Object $params) {
        $this->params = $params;
        return $this;
    }

    /**
     * @return \lean\util\Object
     */
    public function getParams() {
        return $this->getApplication()->getParams();
    }

    /**
     * @param string $key
     * @return string
     */
    public function getParam($key) {
        return $this->getApplication()->getParam($key);
    }

    /**
     * @return string
     */
    public function getAction() {
        return $this->getParams()->has('action')
            ? $this->getParam('action')
            : 'dispatch';
    }

    /**
     * @return Application
     */
    protected function getApplication() {
        return $this->application;
    }

    /**
     * @return \Slim
     */
    public function getSlim() {
        return $this->getApplication()->slim();
    }

    /**
     * @return \Slim_Http_Request
     */
    public function getRequest() {
        return $this->getSlim()->request();
    }

    /**
     * @return \Slim_Http_Response
     */
    public function getResponse() {
        return $this->getSlim()->response();
    }

    /**
     * @param string $url
     * @param int    $status
     */
    public function redirect($url, $status = 302) {
        $this->getSlim()->redirect($url, $status);
    }
}