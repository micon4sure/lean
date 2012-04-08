<?php
namespace lean;

class Partial {

    /**
     * @var Application
     */
    private $application;

    /**
     * @var util\Object
     */
    protected $data;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string      $name
     * @param Application $application
     */
    public function __construct($name, Application $application) {
        $this->name = $name;
        $this->application = $application;
        $this->data = new util\Object();

        $this->init();
    }

    public function getName() {
        return $this->name;
    }

    /**
     * @throws Exception
     */
    public function display() {
        $this->createView()->display();
    }

    /**
     * @return Template
     */
    public function createView() {
        $file = $this->application->getSetting('lean.template.partial.directory') . '/' . strtolower($this->name) . '.php';
        $template = new Template($file);
        $template->setData($this->data->toArray());
        $template->setCallback('urlFor', array($this->getApplication()->slim(), 'urlFor'));
        return $template;
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
     * @magic
     */
    public function __invoke() {
        $this->display();
    }
}