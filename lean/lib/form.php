<?php
namespace lean;

/**
 * HTML form abstraction class
 */ class Form {

    /**
     * Request method get
     */
    const METHOD_GET = 'get';

    /**
     * Request method post
     */
    const METHOD_POST = 'post';

    /**
     * @var string name of the form
     */
    private $name;

    /**
     * @var array instances of form\Element
     */
    private $elements = array();

    /**
     * Form action attribute
     *
     * @var string
     */
    private $action = '';

    /**
     * Form method attribute
     *
     * @var string
     */
    private $method = self::METHOD_POST;

    /**+
     * @param $name string
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * Set the request method
     *
     * @param string $method
     *
     * @return Form
     */
    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * @param string $action
     * @return \lean\Form
     */
    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * Add an element to the form
     *
     * @param form\Element $element
     */
    public function addElement(form\Element $element) {
        $this->elements[$element->getName()] = $element;
        $element->setId($this->name . '_' . $element->getName());
    }

    /**
     * Get an element or null if not existent
     *
     * @param $name
     *
     * @return form\Element|null
     */
    public function getElement($name) {
        return array_key_exists($name, $this->elements)
            ? $this->elements[$name]
            : null;
    }

    /**
     * Populate an array of data to the elements
     *
     * @param array $data
     */
    public function populate(array $data) {
        foreach ($this->elements as $element) {
            if (array_key_exists($element->getId(), $data)) {
                $element->setValue($data[$element->getId()]);
            }
        }
    }

    public function open() {
        printf('<form action="%s" method="%s"/>', $this->action, $this->method);
    }

    public function close() {
        echo '</form>';
    }

    /**
     * Display an element
     *
     * @param $name string
     */
    public function display($name) {
        $this->getElement($name)->display();
    }

    /**
     * Display label
     *
     * @param $name string
     */
    public function displayLabel($name, $label) {
        printf('<label for="%s">%s</label>', $this->getElement($name)->getId(), $label);
    }
}