<?php
namespace lean;

use lean\util\Object;

class Partial {

    /**
     * @var Application
     */
    private $application;

    /**
     * @var Object
     */
    protected $data;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Template
     */
    private $view;

    /**
     * @param string      $name
     * @param Application $application
     */
    public function __construct($name, Application $application) {
        $this->name = $name;
        $this->application = $application;
        $this->data = new Object();
        $this->view = $this->createView();
    }

    /**
     * Get the name of this partial
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @throws Exception
     */
    public function display() {
        $this->view->setData($this->data->toArray());
        $this->view->display();
    }

    /**
     * get partial directory
     * @return mixed
     */
    protected function getPartialDirectory() {
        return $this->application->getSetting('lean.template.partial.directory');
    }

    /**
     * @return Template
     */
    public function createView() {
        $file = $this->getTemplateFile();
        $template = new Template($file);
        $template->setCallback('urlFor', [$this->getApplication(), 'urlFor']);
        $template->setCallback('urlForDefault', [$this->getApplication(), 'urlForDefault']);
        return $template;
    }

    /**
     * @return Template
     */
    public function getView() {
        return $this->view;
    }

    /**
     * @return string
     */
    protected function getTemplateFile() {
        return $this->getPartialDirectory() . '/' . strtolower($this->name) . '.php';
    }

    /**
     * @return Application
     */
    protected function getApplication() {
        return $this->application;
    }

    /**
     * Set up the partial
     */
    public function init() {

    }

    /**
     * overwrite this to set data from within view
     * arguments optional!
     */
    public function data() {
    }

    /**
     * @magic
     */
    public function __invoke() {
        call_user_func_array(array($this, 'data'), func_get_args());
        $this->display();
    }
}