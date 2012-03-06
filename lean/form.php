<?php
namespace lean;

/**
 * HTML form abstraction class
 */
class Form {

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
     * @var string
     */
    private $action = '';
    /**
     * Form method attribute
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
     * Get or set the request method
     * @param null $method
     * @return Form
     */
    public function method($method = null) {
        if(func_num_args() == 1) {
            $this->method = $method;
            return $this;
        }
        return $this->method;
    }

    /**
     * Get or set the request method
     * @param null $method
     * @return Form
     */
    public function action($action = null) {
        if(func_num_args() == 1) {
            $this->action = $action;
            return $this;
        }
        return $this->action;
    }

    /**
     * Add an element to the form
     * @param form\Element $element
     */
    public function addElement(form\Element $element){
        $this->elements[$element->getName()] = $element;
        $element->id($this->name . '_' . $element->getName());
    }

    /**
     * Get an element or null if not existent
     * @param $name
     * @return form\Element|null
     */
    public function getElement($name) {
        return array_key_exists($name, $this->elements) ? $this->elements[$name] : null;
    }

    /**
     * Populate an array of data to the elements
     * @param array $data
     */
    public function populate(array $data) {
        foreach($this->elements as $element) {
            if(array_key_exists($element->id(), $data))
                $element->value($data[$element->id()]);
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
     * @param $name string
     */
    public function display($name) {
        $this->getElement($name)->display();
    }

    /**
     * Display label
     * @param $name string
     */
    public function displayLabel($name, $label) {
        printf('<label for="%s">%s</label>', $this->getElement($name)->id(), $label);
    }
}