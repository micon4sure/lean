<?php
namespace lean;

/**
 * Application class, leaning on Slim
 */
class Application {

    /**
     * Variable incremented by a hook. Indicates how many times slim has dispatched something
     *
     * @var int
     */
    private $dispatched = 0;

    /**
     * The namespaces the application's controllers will reside in
     *
     * @var string
     */
    private $controllerNamespace;

    /**
     * Singleton instance
     *
     * @var Application
     */
    public static $instance;

    /**
     * @var \Slim
     */
    private $slim;

    /**
     * @param string $controllerNamespace
     * @param array  $slimSettings
     */
    public function __construct($controllerNamespace, $slimSettings = array()) {
        self::$instance = $this;

        $this->controllerNamespace = $controllerNamespace;
        $this->slim = new \Slim($slimSettings);

        // hook into route dispatching.
        // this is necessary so application knows how many routes have been passed/dispatched
        $closure = function() {
            $this->dispatchedAction();
        };
        $closure->bindTo($this);
        $this->slim()->hook('slim.before.dispatch', $closure);
    }

    /**
     * Add the /:controller/:action default route.
     * Action is optional
     * Additional parameters are possible: /foo/bar/qux/baz/kos/asd/wam will call FooController::barAction with params
     * {qux: baz, kos: asd, wam: true}
     *
     * @param int $additionalParameters
     */
    public function registerControllerDefaultRoute($additionalParameters = 3, $params = array()) {
        $pattern = '/:controller(/:action)';
        $addName = 'lean_add';
        for ($i = 0; $i <= ($additionalParameters * 2); $i++) {
            $pattern .= sprintf("(/:$addName$i)");
        }

        $route = $this->registerRoute($pattern, $params);
        $route->setMiddleware(function() use($route, $additionalParameters, $addName) {
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

            // dirty reflection workaround: params is protected
            $reflection = new \ReflectionObject($route);
            $property = $reflection->getProperty('params');
            $property->setAccessible(true);
            $property->setValue($route, $params);
            $property->setAccessible(false);
        });
    }

    /**
     * Register a route with the application
     *
     * @param string $pattern the slim compatible url pattern
     * @param array  $params  params that will be passed on to the controller
     * @param array  $methods methods this controller accepts
     *
     * @return \Slim_Route
     *
     * @throws Exception
     */
    public function registerRoute($pattern, $params = array(), $methods = array(\Slim_Http_Request::METHOD_GET, \Slim_Http_Request::METHOD_POST)) {
        // create dispatching function
        $this->params = isset($this->params)
            ? $this->params
            : array();
        $dispatch = function() use($params) {
            $matched = $this->slim()->router()->getMatchedRoutes();

            // get the correct matched route. go back from the end of the array by n passes
            $offset = (count($matched) - $this->dispatchedAction(false));
            $current = $matched[$offset];

            // merge parameters extracted from uri with hard parameters, passed to registerRoute
            $params = array_merge($current->getParams(), $params);

            if (!isset($params['controller'])) {
                throw new Exception("Route has no controller parameter");
            }
            $action = isset($params['action'])
                ? Text::toCamelCase($params['action']) . 'Action'
                : 'dispatch';

            $controllerClass = $this->getControllerNamespace() . '\\'
                . Text::toCamelCase($params['controller'], true);

            // controller exists?
            if (!class_exists($controllerClass, true)) {
                throw new Exception("Controller of type '$controllerClass' was not found");
            }
            $controller = new $controllerClass($this);

            // controller is of the correct type?
            if (!$controller instanceof Controller) {
                throw new Exception("Controller of type '$controllerClass' is not of type lean\\Controller'");
            }

            // controller action exists?
            if (!method_exists($controller, $action)) {
                throw new Exception("Action '$action' does not exist in controller of type '$controllerClass'");
            }

            $this->slim->applyHook('lean.application.before.dispatch');
            call_user_func(array($controller, $action), new Util_ArrayObject($params));

            $this->slim->applyHook('lean.application.after.dispatch');
        };
        $dispatch->bindTo($this);

        // register dispatch with lean
        $route = $this->slim->router()->map($pattern, $dispatch);
        call_user_func_array(array($route, 'setHttpMethods'), $methods);
        return $route;
    }

    /**
     * @return \Slim
     */
    public function slim() {
        return $this->slim;
    }

    /**
     * @return string
     */
    public function getControllerNamespace() {
        return $this->controllerNamespace;
    }

    /**
     * Get the number of controllers, Slim has dispatched, do not call this on your own, is used internally
     *
     * @param bool $increment
     *
     * @return int
     */
    public function dispatchedAction($increment = true) {
        if ($increment) {
            $this->dispatched++;
        }
        return $this->dispatched;
    }

    /**
     * Run the application
     */
    public function run() {
        $this->slim->run();
    }
}