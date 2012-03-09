<?php
namespace lean;


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
     * @param string $controllerNamespace
     * @param array  $slimSettings
     */
    public function __construct($controllerNamespace, $slimSettings = array()) {
        self::$instance = $this;

        $this->controllerNamespace = $controllerNamespace;
        $this->slim = new \Slim($slimSettings);

        // hook into route dispatching.
        // this is necessary so application nows how many routes have been passed
        //TODO 5.4: closure bind
        $application = $this;
        $this->slim->hook('slim.before.dispatch', function() use($application) {
            $application->dispatched();
        });
    }

    /**
     * Register a route with the application
     *
     * @param string $pattern the slim compatible url pattern
     * @param array  $params  params that will be passed on to the controller
     * @param array  $methods methods this controller accepts
     *
     * @throws Exception
     */
    public function registerRoute($pattern, $params = array(), $methods = array(\Slim_Http_Request::METHOD_GET, \Slim_Http_Request::METHOD_POST)) {
        // create dispatching function
        //TODO 5.4: closure bind
        $application = $this;
        $dispatch = function() use($application, $params) {
            $matched = $application->slim()->router()->getMatchedRoutes();

            // get the correct matched route. go back from the end of the array by n passes
            $dispatched = $application->dispatched(false);
            $offset = (count($matched) - $application->dispatched(false));
            $current = $matched[$offset];

            // merge parameters extracted from uri with hard parameters, passed to registerRoute
            $params = array_merge($current->getParams(), $params);

            if (isset($params['controller'])) {
                $action = isset($params['action'])
                    ? $params['action'] . 'Action'
                    : 'dispatch';

                $controllerClass = $application->getControllerNamespace() . '\\'
                    . Text::restoreCamelCase($params['controller'], true)
                    . 'Controller';

                // controller exists?
                if (!class_exists($controllerClass, true)) {
                    throw new Exception("Controller of type '$controllerClass' was not found");
                }
                $controller = new $controllerClass($application);

                // controller is of the correct type?
                if (!$controller instanceof application\Controller) {
                    throw new Exception("Controller of type '$controllerClass' is not of type lean\Controller'");
                }

                // controller action exists?
                if (!method_exists($controller, $action)) {
                    throw new Exception("'$action' does not exist in controller of type '$controllerClass'");
                }

                call_user_func(array($controller, $action), $params);
            }
        };

        // register dispatch with lean
        $route = $this->slim->router()->map($pattern, $dispatch);
        call_user_func_array(array($route, 'setHttpMethods'), $methods);
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
     * @param bool $increment
     * @return int
     */
    public function dispatched($increment = true) {
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