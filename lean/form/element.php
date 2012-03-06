<?php
namespace lean\form;

/**
 * Abstract form element base class
 */
abstract class Element {

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
     * Get or set the id
     * @param null $id string
     * @return Element|string
     */
    public function id($id=null) {
        if(func_num_args() == 0)
            return $this->id;
        $this->id = $id;
        return $this;
    }

    /**
     * Get or set the value
     * @param $key string
     * @param null $value string
     * @return Element|string
     */
    public function attribute($key, $value=null) {
        if(func_num_args() == 1)
            return array_key_exists($key, $this->attributes) ? $this->attributes[$key] : null;
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Get or set the value
     * @param null $value
     * @return Element|string
     */
    public function value($value=null) {
        if(func_num_args() == 0)
            return $this->value;
        $this->value = $value;
        return $this;
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
     * Create a string of the attributes, fit to be display. e.g.: 'checked="checked" disabled="disabled"'
     * Includes the set css classes
     * @return string
     */
    public function getAttributeString() {
        $string = '';

        $attributes = $this->attributes;
        $attributes['class'] = implode(' ', $this->cssClasses);

        foreach($attributes as $key => $val) {
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