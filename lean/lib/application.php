<?php
namespace lean;

use vitamin\util\Dump;

/**
 * Application class, leaning on Slim
 *
 * default: /foo:bar/qux ::: [] ::: \crmi\controller\foo\Bar
 * crmi.derp /datrzui/qux ::: ['controller' => \crmi\controller\Datrzui] ::: \crmi\controller\Datrzui
 * crmi.derp /datrzui/foo:bar/qux ::: [] ::: \crmi\controller\foo\Bar
 *
 * controller name
 * namespace
 *
 * set:
 * \crmi\controller
 *
 * :controller -> foo:bar \crmi\controller\foo\Bar
 * ['controller' => '\crmi\controller\foo\Bar'] \crmi\controller\foo\Bar
 * ['controller' => '\migration\controller\Derp'] Derp
 *
 *
 */
class Application {

    const DEFAULT_ROUTE_NAME = 'lean_default_action_controller_route';
    const CONTROLLER_NAMESPACE_KEY = '__lean.application.controller.namespace__';

    /**
     * Variable incremented by a hook. Indicates how many times slim has dispatched something
     * Initial value is -1 because it is hooked into slim.before.dispatch.
     * Therefore, it will be 0 once the first dispatch is being made.
     *
     * @var int
     */
    private $dispatched = -1;

    /**
     * @var \Slim
     */
    private $slim;

    /**
     * @var Environment
     */
    private $environment;

    /**
     * Request parameters extracted from the path
     *
     * @var array
     */
    private $params;

    private $controllerNamespace;

    /**
     * @param $controllerNamespace
     */
    public function setControllerNamespace($controllerNamespace) {
        $this->controllerNamespace = $controllerNamespace;
    }

    /**
     * @param Environment $environment
     * @param array       $slimSettings
     */
    public function __construct(Environment $environment, $slimSettings = array()) {
        // check for existence of APPLICATION_ROOT constant
        if (!defined('APPLICATION_ROOT')) {
            throw new Exception("'APPLICATION_ROOT' not defined!");
        }

        //TODO default settings to build environment
        // set environment default settings
        $this->environment = $environment;
        $this->environment->setDefaultSettings($this->getDefaultSettings());

        // create slim
        $this->slim = new \Slim($slimSettings);

        $this->init();

        // hook into route dispatching.
        // this is necessary so application knows how many routes have been passed/dispatched
        $this->slim()->hook('slim.before.dispatch', function() {
            $this->dispatchedRoute();
        });
    }

    /**
     * Gives applications the possibility to set up things like  routes, middleware, etc
     */
    protected function init() {}

    /**
     * Register a route with the application
     *
     * @param        $name
     * @param string $pattern the slim compatible url pattern
     * @param array  $params  params that will be passed on to the controller
     * @param array  $methods methods this controller accepts
     *
     * @throws Exception_UnknownControllerClass
     * @throws \InvalidArgumentException
     * @throws Exception
     * @return \Slim_Route
     */
    public function registerRoute($name, $pattern, array $params = array(), array $methods = array(\Slim_Http_Request::METHOD_GET, \Slim_Http_Request::METHOD_POST)) {
        // ensure argument validity
        if (!is_string($name)) {
            throw new \InvalidArgumentException("Argument 'name' needs to be a string");
        }
        if (!is_string($pattern)) {
            throw new \InvalidArgumentException("Argument 'pattern' needs to be a string");
        }

        // create dispatching function
        $this->params = isset($this->params)
            ? $this->params
            : array();

        $request = $this->slim()->request();
        $dispatch = function() use($params, $request) {
            $matched = $this->slim()->router()->getMatchedRoutes($request->getMethod(), $request->getResourceUri());

            // get the correct matched route. go back from the end of the array by n passes
            $offset = $this->dispatchedRoute(false);
            $current = $matched[$offset];

            // merge parameters extracted from uri with hard parameters, passed to registerRoute
            $params = array_merge($params, $current->getParams());

            if (!isset($params['controller'])) {
                throw new Exception(sprintf("Route with pattern '%s' has no controller parameter", $current->getPattern()));
            }

            // assemble action
            $action = isset($params['action']) ? $params['action'] : 'dispatch';
            // - force lowercase action names
            if($action != strtolower($action)) {
                throw new Exception('Action parameter needs to be lowercase. Camel cased action names need to be hyphenated (fooBar -> foo-bar)');
            }
            $action = Text::toCamelCase($action) . 'Action';

            // equalize controller names
            // foo:bar -> default\namespace\foo:bar
            $class = $params['controller'];
            $namespace = $this->controllerNamespace;
            if(isset($params[self::CONTROLLER_NAMESPACE_KEY])) {
                $class =  $params[self::CONTROLLER_NAMESPACE_KEY].'\\'.$params['controller'];
                $namespace = $params[self::CONTROLLER_NAMESPACE_KEY];
            } else {
                if(Text::left($class, $namespace) != $namespace) {
                    $parts = explode('\\', $class);
                    array_pop($parts);
                    $namespace = implode('\\', $parts);
                }
                $class = str_replace('\\', ':', $class);
            }
            $controller = strtolower(Text::offsetLeft($class, Text::len($namespace) + 1));

            // possibility for sub controllers
            // sub controllers are seperated by colons in the url: foo:bar:qux will resolve into the controller foo\bar\Qux
            $exploded = $origin = explode(':', $controller);
            $controllerClassName = Text::toCamelCase(array_pop($exploded), true);
            if(count($exploded) > 0) {
                $controllerClass = $namespace . '\\' . implode('\\', $exploded) . '\\' . $controllerClassName;
            } else {
                $controllerClass = $namespace . '\\' . $controllerClassName;
            }

            // controller exists?
            if (!class_exists($controllerClass, true)) {
                throw new Exception_UnknownControllerClass("Controller of type '$controllerClass' was not found");
            }
            $controller = new $controllerClass($this);
            $controller->setOrigin($origin);

            // controller is of the correct type?
            if (!$controller instanceof Controller) {
                throw new Exception("Controller of type '$controllerClass' is not of type lean\\Controller'");
            }

            $params = new \lean\util\Object($params);

            $this->setParams($params);
            $controller->init();

            $this->dispatchAction($controller, $action);
        };

        // register dispatch with lean
        $route = $this->slim()->router()->map($pattern, $dispatch);
        $route->name($name);
        call_user_func_array(array($route, 'setHttpMethods'), $methods);
        return $route;
    }

    /**
     * dispatch action
     *
     * @param Controller $controller
     * @param            $action
     *
     * @throws Exception_UnknownControllerAction
     * @throws \Exception
     */
    public function dispatchAction(Controller $controller, $action) {
        try {
            // controller action exists?
            if (!method_exists($controller, $action)) {
                //$this->slim()->pass();
                throw new Exception_UnknownControllerAction("Action '$action' does not exist in controller of type '".get_class($controller)."'");
            }
            try {
                call_user_func(array($controller, $action));
            } catch(\Exception $e) {
                // don't print controller output in case of an exception
                ob_end_clean();
                throw $e;
            }
        } catch(Exception_Forward $e) {
            $this->dispatchAction($controller, $e->getDestination().'Action');
        }
    }

    /**
     * Add the /:controller/:action default route.
     * Action is optional
     *
     * @param string $namespace
     * @param array  $params
     *
     * @return \Slim_Route
     */
    public function registerControllerDefaultRoute($namespace, $params = array()) {
        $params[self::CONTROLLER_NAMESPACE_KEY] = $namespace;
        $route = $this->registerRoute(self::DEFAULT_ROUTE_NAME, '/:controller(/:action)(/:id)', $params);
        return $route;
    }

    /**
     * Set request parameters, remove with 5.4 and closure binding or set private
     *
     * @param \lean\util\Object $params
     */
    public function setParams(util\Object $params) {
        $this->params = $params;
    }

    /**
     * @return Object
     */
    public function getParams() {
        return $this->params;
    }

    public function getParam($key) {
        return $this->getParams()->get($key);
    }

    /**
     * @return Slim
     */
    public function slim() {
        return $this->slim;
    }

    /**
     * get default settings
     *
     * @return array default settings
     */
    protected function getDefaultSettings() {
        $settings = array();
        // environment
        $settings['lean.environment.name'] = 'development';
        $settings['lean.environment.file'] = APPLICATION_ROOT . '/config/environment.ini';
        // templates
        $settings['lean.template.directory'] = APPLICATION_ROOT . '/template';
        $settings['lean.template.document.directory'] = APPLICATION_ROOT . '/template/document';
        $settings['lean.template.layout.directory'] = APPLICATION_ROOT . '/template/layout';
        $settings['lean.template.view.directory'] = APPLICATION_ROOT . '/template/view';
        $settings['lean.template.partial.directory'] = APPLICATION_ROOT . '/template/partial';
        $settings['lean.template.wrapper.directory'] = APPLICATION_ROOT . '/template/wrapper';
        return $settings;
    }

    /**
     * Get an application setting
     *
     * @param string $setting
     * @return mixed setting value
     * @throws Exception
     */
    public function getSetting($setting) {
        return $this->environment->get($setting);
    }

    /**
     * @return Environment
     */
    public function getEnvironment() {
        return $this->environment;
    }

    /**
     * Get the number of routes, Slim has dispatched.
     * Do not call unless you know about the implications
     *
     * @param bool $increment
     *
     * @return int
     */
    private function dispatchedRoute($increment = true) {
        return $increment
            ? $this->dispatched++
            : $this->dispatched;
    }

    /**
     * Run the application
     */
    public function run() {
        $this->slim()->run();
    }

    /**
     * Reload current page
     */
    public function reload() {
        $this->slim()->redirect($_SERVER['REQUEST_URI']);
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
    public function urlFor($name, $params = [], $get = null) {
        return $this->slim()->urlFor($name, $params)  . ($get ? '?' . http_build_query($get) : '');
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
        // create params array
        $params = ['controller' => $controller];
        if($action !== null) {
            $params['action'] = $action;
        }
        if($id !== null) {
            $params['id'] = $id;
        }
        return $this->urlFor(self::DEFAULT_ROUTE_NAME, $params, $get);
    }
}