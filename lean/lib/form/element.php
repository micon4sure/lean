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
    private $label;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string id of the element. Is set by the form after adding
     */
    private $id;

    /**
     * @var array
     */
    private $validators = array();

    /**
     * @param $name
     */
    public function __construct($name, $label = '') {
        $this->name = $name;
        $this->label = $label;
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
     * @param $key
     * @param $value
     * @return Element
     */
    public function setAttribute($key, $value) {
        $this->attributes[$key] = $value;
        return $this;
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
     * @param $label
     * @return Element
     */
    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * @abstract
     * Display the form element
     */
    public abstract function display();

    /**
     * @param bool  $elementClasses set element css classes to label?
     * @param array $attributes
     * @internal param $label
     */
    public function displayLabel($elementClasses = true, $attributes = array()) {
        $attributeStrings = array();
        $attributes['for'] = $this->getId();
        if ($elementClasses) {
            $attributes['class'] = implode(' ', $this->cssClasses);
        }
        foreach ($attributes as $key => $value) {
            $attributeStrings[] = "$key=\"$value\"";
        }

        printf('<label %s>%s</label>',
            implode(' ', $attributeStrings),
            $this->getLabel());
    }

    /**
     * @param Validator $validator
     */
    public function addValidator(Validator $validator) {
        $this->validators[] = $validator;
    }

    /**
     * @return array
     */
    public function getErrors() {
        $errors = array();
        $this->isValid($errors);
        return $errors;
    }

    /**
     * @param $errorMessages
     * @return bool
     */
    public function isValid(&$errorMessages = array()) {
        foreach ($this->validators as $validator) {
            if (!$validator->isValid($this->getValue(), $errorMessages)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return string the captured display output
     */
    public function render() {
        ob_start();
        $this->display();
        return ob_get_clean();
    }
}