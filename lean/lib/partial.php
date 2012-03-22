<?php
namespace lean;

class Partial {

    /**
     * @var Application
     */
    private $application;

    /**
     * @var Util_ArrayObject
     */
    protected $data;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param             $name string
     * @param Application $application
     */
    public function __construct(Application $application, $name = null) {
        $this->name = $name;
        $this->application = $application;
        $this->data = new Util_ArrayObject();

        $this->init();
    }

    /**
     *
     */
    public function display() {
        if ($this->name === null) {
            throw new Exception('Partial name is null');
        }
        $file = $this->application->getSetting('lean.partial.directory') . '/' . strtolower($this->name) . '.php';
        $template = new Template($file);
        $template->setData($this->data->data());
        $template->display();
    }

    /**
     * get application
     *
     * @return Application
     */
    protected function getApplication() {
        return $this->application;
    }

    /**
     *
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