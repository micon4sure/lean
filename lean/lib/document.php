<?php
namespace lean;

/**
 * Representst the document
 * TODO do not extend template anymore and delegate?
 */
class Document extends Template {

    /**
     * @var string
     */
    protected $title = '';
    /**
     * @var array
     */
    protected $scripts = array();
    /**
     * @var array
     */
    protected $styles = array('css' => array(), 'less' => array());

    /**
     * @param string $file
     */
    public function __construct($file) {
        parent::__construct($file);
    }

    /**
     * @param string $style
     */
    public function addCSSheet($style) {
        $this->styles['css'][] = $style;
    }

    /**
     * @param string $style
     */
    public function addLESSheet($style) {
        $this->styles['less'][] = $style;
    }

    /**
     * @param string $script
     */
    public function addScript($script) {
        $this->scripts[] = $script;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }
    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }
}