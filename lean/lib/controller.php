<?php

namespace lean;

class Controller {

    /**
     * @var array
     */
    private $origin = [];

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
     * forward to action
     * @param $action
     * @throws Exception_Forward
     */
    public function forward($action) {
        throw new Exception_Forward($action);
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

    /**
     * Create a URL and append a get query string
     *
     * @param $name
     * @param $params
     * @param $get
     *
     * @return string
     */
    public function urlFor($name, $params = array(), $get = null) {
        return $this->getApplication()->urlFor($name, $params, $get);
    }

    /**
     * Generate a URL from the default route
     * @param $controller
     * @param null|string $action
     * @param null|int $id
     * @param null|array $get
     * @return string
     */
    public function urlForDefault($controller, $action = null, $id = null, $get = null) {
        return $this->getApplication()->urlForDefault($controller, $action, $id, $get);
    }

    /**
     * @param array $origin
     */
    public function setOrigin(array $origin) {
        $this->origin = $origin;
    }

    /**
     * @return array
     */
    public function getOrigin() {
        return $this->origin;
    }
}