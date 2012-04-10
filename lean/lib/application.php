<?php
namespace lean;

/**
 * Application class, leaning on Slim
 */
class Application {

    const DEFAULT_ROUTE_NAME = 'lean_default_action_controller_route';

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
     * @var array
     */
    private $params;

    /**
     * @param Environment $environment
     * @param array  $slimSettings
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

        // hook into route dispatching.
        // this is necessary so application knows how many routes have been passed/dispatched
        $THIS = $this;
        $closure = function() use ($THIS) {
            $THIS->dispatchedRoute();
        };
        $this->slim()->hook('slim.before.dispatch', $closure);
    }

    /**
     * Register a route with the application
     *
     * @param        $name
     * @param string $pattern the slim compatible url pattern
     * @param array  $params  params that will be passed on to the controller
     * @param array  $methods methods this controller accepts
     *
     * @throws Exception
     * @return \Slim_Route
     */
    public function registerRoute($name, $pattern, array $params = array(), array $methods = array(\Slim_Http_Request::METHOD_GET, \Slim_Http_Request::METHOD_POST)) {
        // ensure argument validity
        if(!is_string($name))
            throw new \InvalidArgumentException("Argument 'name' needs to be a string");
        if(!is_string($pattern))
            throw new \InvalidArgumentException("Argument 'pattern' needs to be a string");

        // create dispatching function
        $this->params = isset($this->params)
            ? $this->params
            : array();

        $THIS = $this;
        $dispatch = function() use($THIS, $params) {
            $matched = $THIS->slim()->router()->getMatchedRoutes();

            // get the correct matched route. go back from the end of the array by n passes
            $offset = $THIS->dispatchedRoute(false);
            $current = $matched[$offset];

            // merge parameters extracted from uri with hard parameters, passed to registerRoute
            $params = array_merge($params, $current->getParams());

            if (!isset($params['controller'])) {
                throw new Exception(sprintf("Route with pattern '%s' has no controller parameter", $current->getPattern()));
            }
            $action = isset($params['action'])
                ? Text::toCamelCase($params['action']) . 'Action'
                : 'dispatch';

            $controllerClass = Text::toCamelCase($params['controller'], true);

            // controller exists?
            if (!class_exists($controllerClass, true)) {
                //$this->slim()->pass();
                throw new Exception("Controller of type '$controllerClass' was not found");
            }
            $controller = new $controllerClass($THIS);

            // controller is of the correct type?
            if (!$controller instanceof Controller) {
                throw new Exception("Controller of type '$controllerClass' is not of type lean\\Controller'");
            }

            // controller action exists?
            if (!method_exists($controller, $action)) {
                //$this->slim()->pass();
                throw new Exception("Action '$action' does not exist in controller of type '$controllerClass'");
            }

            $params = new \lean\util\Object($params);

            $this->setParams($params);
            $controller->init();
            call_user_func(array($controller, $action));
        };

        // register dispatch with lean
        $route = $this->slim()->router()->map($pattern, $dispatch);
        $route->name($name);
        call_user_func_array(array($route, 'setHttpMethods'), $methods);
        return $route;
    }

    /**
     * Add the /:controller/:action default route.
     * Action is optional
     * Additional parameters are possible: /foo/bar/qux/baz/kos/asd/wam will call FooController::barAction with params
     * {qux: baz, kos: asd, wam: true}
     *
     * @param string $controllerNamespace
     * @param int $additionalParameters
     * @return \Slim_Route
     */
    public function registerControllerDefaultRoute($controllerNamespace, $additionalParameters = 3, $params = array()) {
        $pattern = '/:controller(/:action)';
        $addName = 'lean_add';
        for ($i = 0; $i <= ($additionalParameters * 2); $i++) {
            $pattern .= sprintf("(/:$addName$i)");
        }

        $route = $this->registerRoute(self::DEFAULT_ROUTE_NAME, $pattern, $params);
        $route->setMiddleware(function() use($route, $additionalParameters, $addName, $controllerNamespace) {
            $params = $route->getParams();
            // loop through the additional parameter key(/value) pairs
            for ($i = 0; $i < count($params); $i += 2) {
                // break if there are no more additional params
                if (!array_key_exists($addName . $i, $params)) {
                    break;
                }

                // the key is always the even one
                $key = $params[$addName . $i];
                unset($params[$addName . $i]);

                // get the value
                $valueKey = $addName . ($i + 1);
                if (array_key_exists($valueKey, $params)) {
                    // a value does exist, assign it
                    $params[$key] = $params[$valueKey];
                    unset($params[$valueKey]);
                }
                else {
                    // key has no value, assign true
                    $params[$key] = true;
                }
            }

            // prepend controler namespace to controller name
            // and camelcase + ucfirst
            $params['controller'] = $controllerNamespace . '\\'
                . \lean\Text::toCamelCase($params['controller'], true);

            // dirty reflection workaround: params is protected
            $reflection = new \ReflectionObject($route);
            $property = $reflection->getProperty('params');
            $property->setAccessible(true);
            $property->setValue($route, $params);
            $property->setAccessible(false);
        });

        return $route;
    }

    /**
     * Set request parameters, remove with 5.4 and closure binding or set private
     * @param Object $params
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
     * @return \Slim
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
     * TODO private with 5.4 and closure binding
     *
     * @param bool $increment
     *
     * @return int
     */
    public function dispatchedRoute($increment = true) {
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
}