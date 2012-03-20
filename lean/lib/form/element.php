<?php
namespace lean\form;

/**
 * Abstract form element base class
 */ abstract class Element {

    /**
     * @var array html attributes in array form
     */
    private $attributes = array();

    /**
     * @var array css classes
     */
    private $cssClasses = array();

    /**
     * @var the name of the form. Also used to identify it inside of the form
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string id of the element. Is set by the form after adding
     */
    private $id;

    /**
     * @param $name
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $id
     * @return \lean\form\Element
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $key
     * @return null|string
     */
    public function getAttribute($key) {
        return array_key_exists($key, $this->attributes)
            ? $this->attributes[$key]
            : null;
    }

    /**
     * @param string $key
     * @param string $value
     * @internal param string $id
     * @return \lean\form\Element
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param $class string
     * @return Element
     */
    public function addCssClass($class) {
        $this->cssClasses[] = $class;
        return $this;
    }

    /**
     * Create a string of the attributes, fit to be displayed. e.g.: 'checked="checked" disabled="disabled"'
     * Includes the set css classes.
     *
     * @return string
     */
    public function getAttributeString() {
        $string = '';

        $attributes = $this->attributes;
        $attributes['class'] = implode(' ', $this->cssClasses);

        foreach ($attributes as $key => $val) {
            $string .= sprintf(' %s="%s"', $key, htmlspecialchars($val));
        }
        return $string;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @abstract
     * Display the form element
     */
    public abstract function display();

    /**
     * @return string the captured display output
     */
    public function render() {
        ob_start();
        $this->display();
        return ob_get_clean();
    }
}