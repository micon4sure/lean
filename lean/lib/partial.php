<?php
namespace lean;

abstract class Partial {

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
    public function __construct($name) {
        $this->name = $name;
        $this->data = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);

        $this->init();
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
        $this->createView()->display();
    }

    /**
     * @return Template
     */
    public abstract function createView();

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